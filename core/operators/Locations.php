<?php
/**
* @package ConSim for phpBB3.1
*
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*/

namespace consim\core\operators;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Operator for all locations, that you can travel
*/
class RouteLocations
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

    /**
 	* Constructor
 	*
 	* @param \phpbb\db\driver\driver_interface    $db                          Database object
    * @param ContainerInterface                	  $container       	           Service container interface
    * @param string                               $consim_route_table          Name of the table used to store data
 	* @param string                               $consim_location_table       Name of the table used to store data
    * @param string                               $consim_location_type_table  Name of the table used to store data
    * @param string                               $consim_province_table       Name of the table used to store data
    * @param string                               $consim_country_table        Name of the table used to store data
 	* @access public
 	*/
 	public function __construct(\phpbb\db\driver\driver_interface $db,
                                ContainerInterface $container,
                                $consim_route_table,
                                $consim_location_table,
                                $consim_location_type_table,
                                $consim_province_table,
                                $consim_country_table)
 	{
        $this->db = $db;
        $this->container = $container;
        $this->consim_route_table = $consim_route_table;
 		$this->consim_location_table = $consim_location_table;
        $this->consim_location_type_table = $consim_location_type_table;
        $this->consim_province_table = $consim_province_table;
        $this->consim_country_table = $consim_country_table;
 	}

    /**
	* Get all destination, which can be arrive at the start location
	*
	* @param int $start start location
	* @return array Array of location Entity
	* @access public
	*/
	public function getAllDestinations($start)
	{
        $entities = array();

		$sql = 'SELECT l.id, l.name, t.name AS type, p.name AS province, c.name AS country, r.time
            FROM phpbb_consim_routes r
			LEFT JOIN ' . $this->consim_location_table . ' l ON (r.start_id = l.id AND r.start_id <> '. (int) $start .') OR (r.end_id = l.id AND r.end_id <> '. (int) $start .')
            LEFT JOIN ' . $this->consim_location_type_table . ' t ON l.type = t.id
            LEFT JOIN ' . $this->consim_province_table . ' p ON l.province = p.id
            LEFT JOIN ' . $this->consim_country_table . ' c ON p.country = c.id
			WHERE r.start_id = ' . (int) $start .' OR r.end_id = '. (int) $start;
		$result = $this->db->sql_query($sql);

        while($row = $this->db->sql_fetchrow($result))
        {
            $data= array(
                'id'        => $row['id'],
                'name'      => $row['name'],
                'type'      => $row['type'],
                'province'  => $row['province'],
                'country'   => $row['country'],
                'time'      => $row['time'],
            );
            $data = $this->container->get('consim.core.entity.RouteLocation')->import($data);

            $entities[] = $data;
        }
        $this->db->sql_freeresult($result);

		return $entities;
	}

    /**
	* Set all destination, which can be arrive at the start location
    * as SELECT to template
	*
	* @param int $start start location
	* @param object $template
	* @access public
	*/
	public function setAllDestinationsToTemplate($start, $template, $helper)
	{
        $entities = $this->getAllDestinations($start);

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

}
