<?php
/**
* @package ConSim for phpBB3.1
*
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*/

namespace consim\core\service;

use consim\core\entity\Location;
use consim\core\entity\RouteLocation;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Operator for all locations, that you can travel
*/
class LocationService
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var ContainerInterface */
	protected $container;

	/** @var  \consim\core\service\UserService */
	protected $userService;

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

	/** @var Location|null  */
	protected $currentLocation = null;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface	$db									Database object
	 * @param ContainerInterface					$container						Service container interface
	 * @param \consim\core\service\UserService		$userService					UserService object
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
		\consim\core\service\UserService $userService,
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
		$this->userService = $userService;
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
	 * Return location of current user
	 *
	 * @return Location
	 */
	public function getCurrentLocation()
	{

		if(null === $this->currentLocation)
		{
			$this->currentLocation = $this->getLocation($this->userService->getCurrentUser()->getLocationId());
		}

		return $this->currentLocation;
	}

	/**
	 * @param $locationId
	 * @return Location
	 */
	public function getLocation($locationId)
	{
		if(null !== $this->currentLocation && $this->currentLocation->getId() == $locationId)
		{
			return $this->currentLocation;
		}

		return $this->container->get('consim.core.entity.location')->load($locationId);
	}

	/**
	 * Return start and end location of route
	 * e.g: Location[start] and Location[end]
	 *
	 * @param int $start_id
	 * @param \consim\core\entity\Route $route
	 * @return \consim\core\entity\Location[]
	 */
	public function getLocationFromRoute($start_id, \consim\core\entity\Route $route)
	{
		/** @var Location[] $locations */
		$locations = array();

		//if start equal start of route
		if($start_id == $route->getStartLocationId())
		{
			$locations['start'] = $this->getLocation($start_id);
			$locations['end'] = $this->getLocation($route->getEndLocationId());
		}
		//if reverse way
		else
		{
			$locations['start'] = $this->getLocation($route->getEndLocationId());
			$locations['end'] = $this->getLocation($route->getStartLocationId());
		}

		return $locations;
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
	public function setAllRouteDestinationsToTemplate($start, \phpbb\template\template $template, \phpbb\controller\helper $helper)
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
				'ACTION'  		=> $helper->route('consim_core_travel_start', array('travel_id' => $entity->getId(), 'hash' => generate_link_hash("travel_".$entity->getId()))),
			);

			$template->assign_block_vars('destination', $select);
		}
	}
}
