<?php
/**
* @package ConSim for phpBB3.1
*
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*/

namespace consim\core\operators;

use consim\core\entity\Building;
use consim\core\entity\RouteLocation;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Operator for all locations, that you can travel
*/
class Locations
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var ContainerInterface */
	protected $container;

	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_route_table;
	protected $consim_location_table;
	protected $consim_location_type_table;
	protected $consim_province_table;
	protected $consim_country_table;
	protected $consim_building_table;
	protected $consim_building_type_table;
	protected $consim_work_table;
	protected $consim_skill_table;
	protected $consim_item_table;

	/**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface	$db								Database object
	* @param ContainerInterface					$container						Service container interface
	* @param string								$consim_route_table				Name of the table used to store data
	* @param string								$consim_location_table			Name of the table used to store data
	* @param string								$consim_location_type_table		Name of the table used to store data
	* @param string								$consim_province_table			Name of the table used to store data
	* @param string								$consim_country_table			Name of the table used to store data
	* @param string								$consim_building_table			Name of the table used to store data
	* @param string								$consim_building_type_table		Name of the table used to store data
	* @param string								$consim_work_table				Name of the table used to store data
	* @param string								$consim_skill_table				Name of the table used to store data
	* @param string								$consim_item_table				Name of the table used to store data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db,
								ContainerInterface $container,
								$consim_route_table,
								$consim_location_table,
								$consim_location_type_table,
								$consim_province_table,
								$consim_country_table,
								$consim_building_table,
								$consim_building_type_table,
								$consim_work_table,
								$consim_skill_table,
								$consim_item_table)
	{
		$this->db = $db;
		$this->container = $container;
		$this->consim_route_table = $consim_route_table;
		$this->consim_location_table = $consim_location_table;
		$this->consim_location_type_table = $consim_location_type_table;
		$this->consim_province_table = $consim_province_table;
		$this->consim_country_table = $consim_country_table;
		$this->consim_building_table = $consim_building_table;
		$this->consim_building_type_table = $consim_building_type_table;
		$this->consim_work_table = $consim_work_table;
		$this->consim_skill_table = $consim_skill_table;
		$this->consim_item_table = $consim_item_table;
	}

	/**
	* Get all buildings in the location
	*
	* @param int $location_id Location ID
	* @return Building[]
	* @access public
	*/
	public function getAllBuildings($location_id)
	{
		$entities = array();

		$sql = 'SELECT lb.id, lb.name, lb.description, b.id AS type_id, b.name AS type_name
			FROM ' . $this->consim_building_table . ' lb
			LEFT JOIN ' . $this->consim_building_type_table . ' b ON lb.type_id = b.id
			WHERE lb.location_id = ' . (int) $location_id;
		$result = $this->db->sql_query($sql);

		while($row = $this->db->sql_fetchrow($result))
		{
			$entities[] = $this->container->get('consim.core.entity.building')->import($row);
		}
		$this->db->sql_freeresult($result);

		return $entities;
	}

	/**
	* Get all destination, which can be arrive at the start location
	*
	* @param int $start start location
	* @return RouteLocation[]
	* @access public
	*/
	public function getAllRouteDestinations($start)
	{
		$entities = array();

		$sql = 'SELECT l.id, l.name, t.name AS type, p.name AS province, c.name AS country, r.time
			FROM phpbb_consim_routes r
			LEFT JOIN ' . $this->consim_location_table . ' l ON (r.start_location_id = l.id AND r.start_location_id <> '. (int) $start .')
																OR (r.end_location_id = l.id AND r.end_location_id <> '. (int) $start .')
			LEFT JOIN ' . $this->consim_location_type_table . ' t ON l.type_id = t.id
			LEFT JOIN ' . $this->consim_province_table . ' p ON l.province_id = p.id
			LEFT JOIN ' . $this->consim_country_table . ' c ON p.country_id = c.id
			WHERE r.start_location_id = ' . (int) $start .' OR r.end_location_id = '. (int) $start;
		$result = $this->db->sql_query($sql);

		while($row = $this->db->sql_fetchrow($result))
		{
			$entities[] = $this->container->get('consim.core.entity.route_location')->import($row);
		}
		$this->db->sql_freeresult($result);

		return $entities;
	}

	/**
	* Set all destination, which can be arrive at the start location
	* as SELECT to template
	*
	* @param int						$start
	* @param \phpbb\template\template	$template
	* @param \phpbb\controller\helper	$helper
	* @return void
	* @access public
	*/
	public function setAllRouteDestinationsToTemplate($start, $template, $helper)
	{
		$entities = $this->getAllRouteDestinations($start);

		foreach ($entities as $entity)
		{
			$select = array(
				'NAME'	     	=> $entity->getName(),
				'TYPE'  		=> $entity->getType(),
				'PROVINCE'		=> $entity->getProvince(),
				'COUNTRY'		=> $entity->getCountry(),
				'TIME'          => gmdate('i:s', $entity->getTime()),
				'URL'           => $helper->route('consim_core_location', array('location_id' => $entity->getId())),
				'ACTION'  		=> $helper->route('consim_core_travel', array('travel_id' => $entity->getId(), 'hash' => generate_link_hash("travel_".$entity->getId()))),
			);

			$template->assign_block_vars('destination', $select);
		}
	}

	/**
	 * Get all works of building type
	 *
	 * @param int $buildingType
	 * @return \consim\core\entity\Work[]
	 * @access public
	 */
	public function getWorks($buildingType)
	{
		$entities = array();

		$sql = 'SELECT w.id, w.name, w.description, w.duration, w.building_type_id, 
				w.condition_id, w.condition_value, w.output_id, w.output_value,
				COALESCE(s.name,"") AS condition_name, COALESCE(i.name, "") AS output_name
			FROM ' . $this->consim_work_table . ' w
			LEFT JOIN '. $this->consim_skill_table .' s ON s.id = w.condition_id
			LEFT JOIN '. $this->consim_item_table .' i ON i.id = w.output_id 
			WHERE building_type_id = '.  $buildingType;
		$result = $this->db->sql_query($sql);
		
		while($row = $this->db->sql_fetchrow($result))
		{
			$entities[] = $this->container->get('consim.core.entity.work')->import($row);
		}
		$this->db->sql_freeresult($result);

		return $entities;
	}

}
