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
					'COLUMNS'	=> array(
						'id'					=> array('UINT:8', 0),
						'name'					=> array('VCHAR:255', ''),
						'description'			=> array('TEXT_UNI', ''),
						'image'					=> array('VCHAR:255', ''),
						'type_id'				=> array('UINT:8', 0),
						'province_id'			=> array('UINT:8', 0),
					),
					'PRIMARY_KEY'	=> array('id'),
					'KEYS'			=> array(
						'type_id'				=> array('INDEX', 'type_id'),
					),
				),
				$this->table_prefix . 'consim_location_types'	=> array(
					'COLUMNS'	=> array(
						'id'					=> array('UINT:8', 0),
						'name'					=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> array('id'),
				),
				$this->table_prefix . 'consim_routes'	=> array(
					'COLUMNS'	=> array(
						'id'					=> array('UINT:8', 0),
						'start_location_id'		=> array('UINT:8', 0),
						'end_location_id'		=> array('UINT:8', 0),
						'time'					=> array('UINT:8', 0),
					),
					'PRIMARY_KEY'	=> array('id'),
					'KEYS'			=> array(
						'start_id'		=> array('INDEX', 'start_location_id'),
						'end_id'		=> array('INDEX', 'end_location_id'),
					),
				),
				$this->table_prefix . 'consim_provinces'	=> array(
					'COLUMNS'	=> array(
						'id'					=> array('UINT:8', 0),
						'name'					=> array('VCHAR:255', ''),
						'country_id'			=> array('UINT:8', 0),
					),
					'PRIMARY_KEY'	=> array('id'),
				),
				$this->table_prefix . 'consim_countries'	=> array(
					'COLUMNS'	=> array(
						'id'					=> array('UINT:8', 0),
						'name'					=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> array('id'),
				),
				$this->table_prefix . 'consim_building_types'	=> array(
					'COLUMNS'	=> array(
						'id'					=> array('UINT:8', 0),
						'name'					=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> array('id'),
				),
				$this->table_prefix . 'consim_buildings'	=> array(
					'COLUMNS'	=> array(
						'id'					=> array('UINT:8', 0),
						'name'					=> array('VCHAR:255', ''),
						'description'			=> array('TEXT', ''),
						'type_id'				=> array('UINT:8', 0),
						'location_id'			=> array('UINT:8', 0),
					),
					'PRIMARY_KEY'	=> array('id'),
				),
				$this->table_prefix . 'consim_works'	=> array(
					'COLUMNS'	=> array(
						'id'					=> array('UINT:8', 0),
						'name'					=> array('VCHAR:255', ''),
						'description'			=> array('TEXT_UNI', ''),
						'duration'				=> array('UINT:8', 0),
						'building_type_id'		=> array('UINT:8', 0),
						'condition_1_id'		=> array('UINT:8', 0),
						'condition_1_trials'	=> array('UINT:8', 0),
						'condition_1_value'		=> array('UINT:8', 0),
						'condition_2_id'		=> array('UINT:8', 0),
						'condition_2_trials'	=> array('UINT:8', 0),
						'condition_2_value'		=> array('UINT:8', 0),
						'condition_3_id'		=> array('UINT:8', 0),
						'condition_3_trials'	=> array('UINT:8', 0),
						'condition_3_value'		=> array('UINT:8', 0),
						'experience_points'		=> array('TEXT_UNI', ''),
					),
					'PRIMARY_KEY'	=> array('id'),
					'KEYS'			=> array(
						'b_type_id'				=> array('INDEX', 'building_type_id'),
					),
				),
				$this->table_prefix . 'consim_work_outputs'	=> array(
					'COLUMNS'	=> array(
						'id'					=> array('UINT:8', 0),
						'work_id'				=> array('UINT:8', 0),
						'success_threshold'		=> array('UINT:8', 0),
						'output_id'				=> array('UINT:8', 0),
						'output_value'			=> array('UINT:8', 0),
					),
					'PRIMARY_KEY'	=> array('id'),
					'KEYS'			=> array(
						'wid'					=> array('INDEX', 'work_id'),
						's'						=> array('INDEX', 'success_threshold'),
					),
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
		/** @var \phpbb\user $user */
		global $user;

		// Load the installation lang file
		$user->add_lang_ext('consim/core', 'consim_install');

		return array(
			array('custom', array(array($this, 'insert_locations'))),
			array('custom', array(array($this, 'insert_location_types'))),
			array('custom', array(array($this, 'insert_routes'))),
			array('custom', array(array($this, 'insert_provinces'))),
			array('custom', array(array($this, 'insert_countries'))),
			array('custom', array(array($this, 'insert_building_types'))),
			array('custom', array(array($this, 'insert_buildings'))),
			array('custom', array(array($this, 'insert_works'))),
			array('custom', array(array($this, 'insert_work_outputs'))),
		);
	}

	public function insert_locations()
	{
		/** @var \phpbb\user $user */
		global $user;

		$locations = array(
			array('id' => 1, 'name' => $user->lang('NOBRI'),
				  'description' => $user->lang('NOBRI_DESC'),
				  'image' => 'locations/isoria_nobri.jpg', 'type_id' => 3, 'province_id' => 1),
			array('id' => 2, 'name' => $user->lang('ALATYR'),
				  'description' => $user->lang('ALATYR_DESC'),
				  'image' => 'locations/isoria_alatyr.jpg', 'type_id' => 2, 'province_id' => 1),
			array('id' => 3, 'name' => $user->lang('GRUSHEVSK'),
				  'description' => $user->lang('GRUSHEVSK_DESC'),
				  'image' => 'locations/isoria_grushevsk.jpg', 'type_id' => 2, 'province_id' => 1),
			array('id' => 4, 'name' => $user->lang('ASTRAKAN'),
				  'description' => $user->lang('ASTRAKAN_DESC'),
				  'image' => 'locations/isoria_astrakan.jpg', 'type_id' => 2, 'province_id' => 1),
			array('id' => 5, 'name' => $user->lang('KUBISHEVSK'),
				  'description' => $user->lang('KUBISHEVSK_DESC'),
				  'image' => 'locations/isoria_kubishevsk.jpg', 'type_id' => 2, 'province_id' => 1),
			array('id' => 6, 'name' => $user->lang('VARNOGRAD'),
				  'description' => $user->lang('VARNOGRAD_DESC'),
				  'image' => 'locations/isoria_varnograd.jpg', 'type_id' => 5, 'province_id' => 1),
			array('id' => 7, 'name' => $user->lang('JEGENSK'),
				  'description' => $user->lang('JEGENSK_DESC'),
				  'image' => 'locations/isoria_jegensk.jpg', 'type_id' => 1, 'province_id' => 1),
			array('id' => 8, 'name' => $user->lang('TURAV'),
				  'description' => $user->lang('TURAV_DESC'),
				  'image' => 'locations/isoria_turav.jpg', 'type_id' => 1, 'province_id' => 1),
			array('id' => 9, 'name' => $user->lang('KIRGANOV'),
				  'description' => $user->lang('KIRGANOV_DESC'),
				  'image' => 'locations/isoria_kirganov.jpg', 'type_id' => 1, 'province_id' => 1),
			array('id' => 10, 'name' => $user->lang('PKD_74'),
				  'description' => $user->lang('PKD_74_DESC'),
				  'image' => 'locations/isoria_pkd_74.jpg', 'type_id' => 6, 'province_id' => 1),
			array('id' => 11, 'name' => $user->lang('SMTU_567_C'),
				  'description' => $user->lang('SMTU_567_C_DESC'),
				  'image' => 'locations/isoria_smtu_567_c.jpg', 'type_id' => 6, 'province_id' => 1),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_locations', $locations);
	}

	public function insert_location_types()
	{
		/** @var \phpbb\user $user */
		global $user;

		$types = array(
			array('id' => 1, 'name' => $user->lang('TOWN')),
			array('id' => 2, 'name' => $user->lang('CITY')),
			array('id' => 3, 'name' => $user->lang('PROVINCIAL_CAPITAL')),
			array('id' => 4, 'name' => $user->lang('CAPITAL')),
			array('id' => 5, 'name' => $user->lang('INDUSTRY_AREA')),
			array('id' => 6, 'name' => $user->lang('MILITARY_AREA')),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_location_types', $types);
	}

	public function insert_routes()
	{
		$routes = array(
			array('id' => 1, 'start_location_id' => 1, 'end_location_id' => 2, 'time' => 360),
			array('id' => 2, 'start_location_id' => 1, 'end_location_id' => 3, 'time' => 300),
			array('id' => 3, 'start_location_id' => 1, 'end_location_id' => 9, 'time' => 480),
			array('id' => 4, 'start_location_id' => 1, 'end_location_id' => 4, 'time' => 360),
			array('id' => 5, 'start_location_id' => 1, 'end_location_id' => 5, 'time' => 300),
			array('id' => 6, 'start_location_id' => 5, 'end_location_id' => 8, 'time' => 300),
			array('id' => 7, 'start_location_id' => 5, 'end_location_id' => 11, 'time' => 300),
			array('id' => 8, 'start_location_id' => 11, 'end_location_id' => 4, 'time' => 540),
			array('id' => 9, 'start_location_id' => 4, 'end_location_id' => 9, 'time' => 420),
			array('id' => 10, 'start_location_id' => 8, 'end_location_id' => 2, 'time' => 360),
			array('id' => 11, 'start_location_id' => 9, 'end_location_id' => 3, 'time' => 360),
			array('id' => 12, 'start_location_id' => 3, 'end_location_id' => 10, 'time' => 360),
			array('id' => 13, 'start_location_id' => 10, 'end_location_id' => 7, 'time' => 240),
			array('id' => 14, 'start_location_id' => 2, 'end_location_id' => 6, 'time' => 360),
			array('id' => 15, 'start_location_id' => 2, 'end_location_id' => 7, 'time' => 300),
			array('id' => 16, 'start_location_id' => 6, 'end_location_id' => 7, 'time' => 480),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_routes', $routes);
	}

	public function insert_provinces()
	{
		/** @var \phpbb\user $user */
		global $user;

		$provinces = array(
			array('id' => 1, 'name' => $user->lang('ISORIA'), 'country_id' => 1),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_provinces', $provinces);
	}

	public function insert_countries()
	{
		/** @var \phpbb\user $user */
		global $user;

		$countries = array(
			array('id' => 1, 'name' => $user->lang('COUNTRY_1')),
			array('id' => 2, 'name' => $user->lang('COUNTRY_2')),
			array('id' => 3, 'name' => $user->lang('COUNTRY_3')),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_countries', $countries);
	}

	public function insert_building_types()
	{
		/** @var \phpbb\user $user */
		global $user;

		$buildings = array(
			array('id' => 1, 'name' => $user->lang('BUILDING_TYPE_1')),
			array('id' => 2, 'name' => $user->lang('BUILDING_TYPE_2')),
			array('id' => 3, 'name' => $user->lang('BUILDING_TYPE_3')),
			array('id' => 4, 'name' => $user->lang('BUILDING_TYPE_4')),
			array('id' => 5, 'name' => $user->lang('BUILDING_TYPE_5')),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_building_types', $buildings);
	}

	public function insert_buildings()
	{
		/** @var \phpbb\user $user */
		global $user;

		$location_buildings = array(
			array('id'			=> 1,
				  'name'		=> $user->lang('BUILDING_1'),
				  'description'	=> $user->lang('BUILDING_1_EXPLAIN'),
				  'type_id'		=> 1,
				  'location_id'	=> 1
			),
			array('id'			=> 2,
				  'name'		=> $user->lang('BUILDING_2'),
				  'description'	=> $user->lang('BUILDING_2_EXPLAIN'),
				  'type_id'		=> 2,
				  'location_id'	=> 1
			),
			array('id'			=> 3,
				  'name'		=> $user->lang('BUILDING_3'),
				  'description'	=> $user->lang('BUILDING_3_EXPLAIN'),
				  'type_id'		=> 3,
				  'location_id'	=> 1
			),
			array('id'			=> 4,
				  'name'		=> $user->lang('BUILDING_4'),
				  'description'	=> $user->lang('BUILDING_4_EXPLAIN'),
				  'type_id'		=> 1,
				  'location_id'	=> 2
			),
			array('id'			=> 5,
				  'name'		=> $user->lang('BUILDING_5'),
				  'description'	=> $user->lang('BUILDING_5_EXPLAIN'),
				  'type_id'		=> 4,
				  'location_id'	=> 2
			),
			array('id'			=> 6,
				  'name'		=> $user->lang('BUILDING_6'),
				  'description'	=> $user->lang('BUILDING_6_EXPLAIN'),
				  'type_id'		=> 5,
				  'location_id'	=> 2
			),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_buildings', $location_buildings);
	}

	public function insert_works()
	{
		/** @var \phpbb\user $user */
		global $user;

		$works = array(
			array(
				'id'					=> 1,
				'name'					=> $user->lang('WORK_1'),
				'description'			=> '',
				'duration'				=> 2,
				'building_type_id'		=> 1,
				'condition_1_id'		=> 1,
				'condition_1_trials'	=> 10,
				'condition_1_value'		=> 25,
				'condition_2_id'		=> 4,
				'condition_2_trials'	=> 5,
				'condition_2_value'		=> 20,
				'condition_3_id'		=> 0,
				'condition_3_trials'	=> 0,
				'condition_3_value'		=> 0,
				'experience_points'		=> serialize(array(0,0,0,1,2)),
			),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_works', $works);
	}

	public function insert_work_outputs()
	{
		$output = array(
			//work id = 1
			array(
				'id'					=> 1,
				'work_id'				=> 1,
				'success_threshold'		=> 1,
				'output_id'				=> 1,
				'output_value'			=> 2,
			),
			array(
				'id'					=> 2,
				'work_id'				=> 1,
				'success_threshold'		=> 2,
				'output_id'				=> 1,
				'output_value'			=> 4,
			),
			array(
				'id'					=> 3,
				'work_id'				=> 1,
				'success_threshold'		=> 3,
				'output_id'				=> 1,
				'output_value'			=> 6,
			),
			array(
				'id'					=> 4,
				'work_id'				=> 1,
				'success_threshold'		=> 4,
				'output_id'				=> 1,
				'output_value'			=> 8,
			),
			array(
				'id'					=> 5,
				'work_id'				=> 1,
				'success_threshold'		=> 5,
				'output_id'				=> 1,
				'output_value'			=> 10,
			),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_work_outputs', $output);
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
				$this->table_prefix . 'consim_building_types',
				$this->table_prefix . 'consim_works',
				$this->table_prefix . 'consim_work_outputs',
			),
		);
	}
}
