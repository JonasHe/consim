<?php

/**
*
* @package ConSim for phpBB3.1
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace consim\core\migrations;

class install_travel extends \phpbb\db\migration\migration
{

	static public function depends_on()
	{
		return array('\consim\core\migrations\install_basics');
	}

	/**
	* Add columns
	*
	* @return array Array of table schema
	* @access public
	*/
	public function update_schema()
	{
        return array(
			'add_tables'		=> array(
				$this->table_prefix . 'consim_locations'	=> array(
					'COLUMNS'      => array(
						'id'                    => array('UINT:8', 0),
						'name'            		=> array('VCHAR:255', ''),
						'type_id'               => array('UINT:8', 0),
                        'province_id'           => array('UINT:8', 0),
					),
					'PRIMARY_KEY'	=> array('id'),
                    'KEYS'			=> array(
						'type_id'		        => array('INDEX', 'type'),
					),
				),
                $this->table_prefix . 'consim_location_types'	=> array(
					'COLUMNS'      => array(
						'id'                    => array('UINT:8', 0),
						'name'            		=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> array('id'),
				),
                $this->table_prefix . 'consim_routes'	=> array(
					'COLUMNS'      => array(
						'id'                    => array('UINT:8', 0),
						'start_location_id'     => array('UINT:8', 0),
                        'end_location_id'       => array('UINT:8', 0),
                        'time'                  => array('UINT:8', 0),
					),
					'PRIMARY_KEY'	=> array('id'),
                    'KEYS'			=> array(
						'start_location_id'     => array('INDEX', 'start_location_id'),
                        'end_location_id'       => array('INDEX', 'end_location_id'),
					),
				),
                $this->table_prefix . 'consim_travels'	=> array(
					'COLUMNS'      => array(
						'id'                    => array('UINT:8', NULL, 'auto_increment'),
						'start_location_id'     => array('UINT:8', 0),
                        'end_location_id'       => array('UINT:8', 0),
					),
					'PRIMARY_KEY'	=> array('id'),
				),
                $this->table_prefix . 'consim_provinces'	=> array(
					'COLUMNS'      => array(
						'id'                    => array('UINT:8', 0),
						'name'            		=> array('VCHAR:255', ''),
                        'country'               => array('UINT:8', 0),
					),
					'PRIMARY_KEY'	=> array('id'),
				),
                $this->table_prefix . 'consim_countries'	=> array(
					'COLUMNS'      => array(
						'id'                    => array('UINT:8', 0),
						'name'            		=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> array('id'),
				),
                $this->table_prefix . 'consim_buildings'	=> array(
					'COLUMNS'      => array(
						'id'                    => array('UINT:8', 0),
						'name'            		=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> array('id'),
				),
                $this->table_prefix . 'consim_location_buildings'	=> array(
					'COLUMNS'      => array(
						'id'                    => array('UINT:8', NULL, 'auto_increment'),
                        'name'                  => array('VCHAR:255', ''),
						'location_id'        	=> array('UINT:8', 0),
                        'building_id'           => array('UINT:8', 0),
					),
					'PRIMARY_KEY'	=> array('id'),
				),
            ),
        );
	}

    /**
    * Add or update data in the database
    *
    * @return array Array of table data
    * @access public
    */
    public function update_data()
    {
        return array(
            array('custom', array(array($this, 'insert_locations'))),
            array('custom', array(array($this, 'insert_location_types'))),
            array('custom', array(array($this, 'insert_routes'))),
            array('custom', array(array($this, 'insert_provinces'))),
            array('custom', array(array($this, 'insert_countries'))),
            array('custom', array(array($this, 'insert_buildings'))),
            array('custom', array(array($this, 'insert_location_buildings'))),
        );
    }

    public function insert_locations()
	{
		$locations = array(
            array('id' => 1, 'name' => 'Kubishevsk', 'type_id' => 2, 'province_id' => 1),
            array('id' => 2, 'name' => 'SMTU 567-C', 'type_id' => 4, 'province_id' => 1),
            array('id' => 3, 'name' => 'Astrakhan', 'type_id' => 2, 'province_id' => 1),
            array('id' => 4, 'name' => 'Nobri', 'type_id' => 3, 'province_id' => 1),
            array('id' => 5, 'name' => 'Zhitomsk', 'type_id' => 1, 'province_id' => 1),
            array('id' => 6, 'name' => 'Alatyr', 'type_id' => 2, 'province_id' => 1),
            array('id' => 7, 'name' => 'Grushevsk', 'type_id' => 2, 'province_id' => 1),
        );
		$this->db->sql_multi_insert($this->table_prefix . 'consim_locations', $locations);
	}

    public function insert_location_types()
	{
		$types = array(
            array('id' => 1, 'name' => 'Dorf'),
            array('id' => 2, 'name' => 'Stadt'),
            array('id' => 3, 'name' => 'Großstadt'),
            array('id' => 4, 'name' => 'Militärbasis'),
        );
		$this->db->sql_multi_insert($this->table_prefix . 'consim_location_types', $types);
	}

    public function insert_routes()
	{
		$routes = array(
            array('id' => 1, 'start_location_id' => 1, 'end_location_id' => 2, 'time' => 60),
            array('id' => 2, 'start_location_id' => 2, 'end_location_id' => 3, 'time' => 240),
            array('id' => 3, 'start_location_id' => 3, 'end_location_id' => 4, 'time' => 480),
            array('id' => 4, 'start_location_id' => 1, 'end_location_id' => 4, 'time' => 120),
            array('id' => 5, 'start_location_id' => 4, 'end_location_id' => 5, 'time' => 30),
            array('id' => 6, 'start_location_id' => 5, 'end_location_id' => 6, 'time' => 240),
            array('id' => 7, 'start_location_id' => 6, 'end_location_id' => 7, 'time' => 540),
            array('id' => 8, 'start_location_id' => 7, 'end_location_id' => 4, 'time' => 180),
        );
		$this->db->sql_multi_insert($this->table_prefix . 'consim_routes', $routes);
	}

    public function insert_provinces()
	{
		$provinces = array(
            array('id' => 1, 'name' => 'Isoria', 'country' => 1),
        );
		$this->db->sql_multi_insert($this->table_prefix . 'consim_provinces', $provinces);
	}

    public function insert_countries()
	{
		$countries = array(
            array('id' => 1, 'name' => 'Bakirien'),
            array('id' => 2, 'name' => 'Tadsowien'),
            array('id' => 3, 'name' => 'Suranien'),
        );
		$this->db->sql_multi_insert($this->table_prefix . 'consim_countries', $countries);
	}

    public function insert_buildings()
	{
		$buildings = array(
            array('id' => 1, 'name' => 'Lokale Administration'),
            array('id' => 2, 'name' => 'Industriekombinat'),
            array('id' => 3, 'name' => 'Bordell'),
        );
		$this->db->sql_multi_insert($this->table_prefix . 'consim_buildings', $buildings);
	}

    public function insert_location_buildings()
	{
		$location_buildings = array(
            array('id' => 1, 'name' => '', 'location_id' => 4, 'building_id' => 1),
            array('id' => 2, 'name' => 'Rotstahl', 'location_id' => 4, 'building_id' => 2),
            array('id' => 3, 'name' => 'Tretmine', 'location_id' => 4, 'building_id' => 3),
        );
		$this->db->sql_multi_insert($this->table_prefix . 'consim_location_buildings', $location_buildings);
	}

	/**
	* Drop columns
	*
	* @return array Array of table schema
	* @access public
	*/
	public function revert_schema()
	{
        return array(
			'drop_tables'		=> array(
				$this->table_prefix . 'consim_locations',
				$this->table_prefix . 'consim_location_types',
                $this->table_prefix . 'consim_routes',
                $this->table_prefix . 'consim_travels',
                $this->table_prefix . 'consim_provinces',
                $this->table_prefix . 'consim_countries',
                $this->table_prefix . 'consim_buildings',
                $this->table_prefix . 'consim_location_buildings',
			),
		);
	}
}
