<?php
/**
* @package ConSim for phpBB3.1
*
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*/

namespace consim\core\entity;

/**
* Entity for a single ressource
*/
class RouteLocation extends Location
{
	/**
	* All of fields of this objects
	*
	**/
	protected static $fields = array(
    	'id'						=> 'integer',
      	'name'                      => 'string',
      	'type'						=> 'string',
      	'province'					=> 'string',
        'country'					=> 'string',
        'time'                      => 'integer',
	);

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned = array(
      	'id',
        'time',
	);

	protected $data;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

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
                                $consim_route_table,
                                $consim_location_table,
                                $consim_location_type_table,
                                $consim_province_table,
                                $consim_country_table)
	{
		$this->db = $db;
        $this->consim_route_table = $consim_route_table;
		$this->consim_location_table = $consim_location_table;
        $this->consim_location_type_table = $consim_location_type_table;
        $this->consim_province_table = $consim_province_table;
        $this->consim_country_table = $consim_country_table;
	}

	/**
	* Load the data from the database for this object
	*
	* @param int $start Start Location
	* @return object $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function load($start)
	{
		$sql = 'SELECT l.id, l.name, t.name AS type, p.name AS province, c.name AS country, r.time
            FROM phpbb_consim_routes r
            LEFT JOIN ' . $this->consim_location_table . ' l ON (r.start_id = l.id AND r.start_id <> '. (int) $start .') OR (r.end_id = l.id AND r.end_id <> '. (int) $start .')
            LEFT JOIN ' . $this->consim_location_type_table . ' t ON l.type = t.id
            LEFT JOIN ' . $this->consim_province_table . ' p ON l.province = p.id
            LEFT JOIN ' . $this->consim_country_table . ' c ON p.country = c.id
			WHERE r.start_id = ' . (int) $start .' OR r.end_id = '. (int) $start;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($row === false)
		{
			throw new \consim\core\exception\out_of_bounds('id');
		}

        $this->data = array(
            'id'        => $row['id'],
            'name'      => $row['name'],
            'type'      => $row['type'],
            'province'  => $row['province'],
            'country'   => $row['country'],
            'time'      => $row['time'],
        );

		return $this;
	}

	/**
	* Get Time
	*
	* @return int time
	* @access public
	*/
	public function getTime()
	{
		return $this->getInteger($this->data['time']);
	}
}
