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
						'x'						=> array('UINT:8', 0),
						'y'						=> array('UINT:8', 0),
						'map_name'				=> array('VCHAR:255', '')
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
				$this->table_prefix . 'consim_markers'	=> array(
					'COLUMNS'	=> array(
						'id'					=> array('UINT:8', 0),
						'name'					=> array('VCHAR:255', ''),
						'x'						=> array('UINT:8', 0),
						'y'						=> array('UINT:8', 0),
						'type'					=> array('UINT:8', 0),
						'map_name'				=> array('VCHAR:255','')
					),
					'PRIMARY_KEY'	=> array('id'),
					/**
					* TODO: fehler! kann weg, oder falsche Spalte?
					'KEYS'			=> array(
						'prvnce_id'					=> array('INDEX', 'prvnce_id'),
					),**/
				),
				$this->table_prefix . 'consim_roads'	=> array(
					'COLUMNS'	=> array(
						'id'					=> array('UINT:8', 0),
						'title'					=> array('VCHAR:255', ''),
						'blocked'				=> array('UINT:1', 0),
						'type'					=> array('UINT:8', 0),
						'prvnce_id'				=> array('UINT:8', 0)
					),
					'PRIMARY_KEY'	=> array('id'),
					'KEYS'			=> array(
						'prvnce_id'					=> array('INDEX', 'prvnce_id'),
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
			array('custom', array(array($this, 'insert_roads'))),
		);
	}

	public function insert_locations()
	{
		/** @var \phpbb\user $user */
		global $user;

		$locations = array(
			array('id' => 1, 'name' => $user->lang('NOBRI'),
				  'description' => $user->lang('NOBRI_DESC'),
				  'image' => 'locations/isoria_nobri.jpg', 'type_id' => 3, 'province_id' => 3, 'x' => 865, 'y' => 275, 'map_name' => 'mainMap'),
			array('id' => 2, 'name' => $user->lang('ALATYR'),
				  'description' => $user->lang('ALATYR_DESC'),
				  'image' => 'locations/isoria_alatyr.jpg', 'type_id' => 2, 'province_id' => 3, 'x' => 895, 'y' => 220, 'map_name' => 'mainMap'),
			array('id' => 3, 'name' => $user->lang('GRUSHEVSK'),
				  'description' => $user->lang('GRUSHEVSK_DESC'),
				  'image' => 'locations/isoria_grushevsk.jpg', 'type_id' => 2, 'province_id' => 3, 'x' => 915, 'y' => 310, 'map_name' => 'mainMap'),
			array('id' => 4, 'name' => $user->lang('ASTRAKAN'),
				  'description' => $user->lang('ASTRAKAN_DESC'),
				  'image' => 'locations/isoria_astrakan.jpg', 'type_id' => 2, 'province_id' => 3, 'x' => 800, 'y' => 350, 'map_name' => 'mainMap'),
			array('id' => 5, 'name' => $user->lang('KUBISHEVSK'),
				  'description' => $user->lang('KUBISHEVSK_DESC'),
				  'image' => 'locations/isoria_kubishevsk.jpg', 'type_id' => 2, 'province_id' => 3, 'x' => 765, 'y' => 260, 'map_name' => 'mainMap'),
			array('id' => 6, 'name' => $user->lang('VARNOGRAD'),
				  'description' => $user->lang('VARNOGRAD_DESC'),
				  'image' => 'locations/isoria_varnograd.jpg', 'type_id' => 5, 'province_id' => 3, 'x' => 940, 'y' => 170, 'map_name' => 'mainMap'),
			array('id' => 7, 'name' => $user->lang('JEGENSK'),
				  'description' => $user->lang('JEGENSK_DESC'),
				  'image' => 'locations/isoria_jegensk.jpg', 'type_id' => 1, 'province_id' => 3, 'x' => 970, 'y' => 230, 'map_name' => 'mainMap'),
			array('id' => 8, 'name' => $user->lang('TURAV'),
				  'description' => $user->lang('TURAV_DESC'),
				  'image' => 'locations/isoria_turav.jpg', 'type_id' => 1, 'province_id' => 3, 'x' => 800, 'y' => 230, 'map_name' => 'mainMap'),
			array('id' => 9, 'name' => $user->lang('KIRGANOV'),
				  'description' => $user->lang('KIRGANOV_DESC'),
				  'image' => 'locations/isoria_kirganov.jpg', 'type_id' => 1, 'province_id' => 3, 'x' => 875, 'y' => 345, 'map_name' => 'mainMap'),
			array('id' => 10, 'name' => $user->lang('PKD_74'),
				  'description' => $user->lang('PKD_74_DESC'),
				  'image' => 'locations/isoria_pkd_74.jpg', 'type_id' => 6, 'province_id' => 3, 'x' => 940, 'y' => 270, 'map_name' => 'mainMap'),
			array('id' => 11, 'name' => $user->lang('SMTU_567_C'),
				  'description' => $user->lang('SMTU_567_C_DESC'),
				  'image' => 'locations/isoria_smtu_567_c.jpg', 'type_id' => 6, 'province_id' => 3, 'x' => 750, 'y' => 305, 'map_name' => 'mainMap'),
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
			array('id' => 3, 'name' => $user->lang('ISORIA'), 'country_id' => 1),
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
			array('id' => 4, 'name' => $user->lang('COUNTRY_4')),
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
			//array('id' => 4, 'name' => $user->lang('BUILDING_TYPE_4')),
			//array('id' => 5, 'name' => $user->lang('BUILDING_TYPE_5')),
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
				  'name'		=> $user->lang('BUILDING_1'),
				  'description'	=> $user->lang('BUILDING_1_EXPLAIN'),
				  'type_id'		=> 1,
				  'location_id'	=> 2
			),
			array('id'			=> 5,
				  'name'		=> $user->lang('BUILDING_2'),
				  'description'	=> $user->lang('BUILDING_2_EXPLAIN'),
				  'type_id'		=> 2,
				  'location_id'	=> 2
			),
			array('id'			=> 6,
				  'name'		=> $user->lang('BUILDING_3'),
				  'description'	=> $user->lang('BUILDING_3_EXPLAIN'),
				  'type_id'		=> 3,
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
				'duration'				=> 1,
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
			array(
				'id'					=> 2,
				'name'					=> $user->lang('WORK_2'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 1,
				'condition_1_id'		=> 1,
				'condition_1_trials'	=> 10,
				'condition_1_value'		=> 30,
				'condition_2_id'		=> 4,
				'condition_2_trials'	=> 5,
				'condition_2_value'		=> 25,
				'condition_3_id'		=> 0,
				'condition_3_trials'	=> 0,
				'condition_3_value'		=> 0,
				'experience_points'		=> serialize(array(0,0,1,2,3)),
			),
			array(
				'id'					=> 3,
				'name'					=> $user->lang('WORK_3'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 1,
				'condition_1_id'		=> 1,
				'condition_1_trials'	=> 8,
				'condition_1_value'		=> 35,
				'condition_2_id'		=> 4,
				'condition_2_trials'	=> 7,
				'condition_2_value'		=> 35,
				'condition_3_id'		=> 0,
				'condition_3_trials'	=> 0,
				'condition_3_value'		=> 0,
				'experience_points'		=> serialize(array(0,1,2,3,4)),
			),
			array(
				'id'					=> 4,
				'name'					=> $user->lang('WORK_4'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 1,
				'condition_1_id'		=> 1,
				'condition_1_trials'	=> 6,
				'condition_1_value'		=> 25,
				'condition_2_id'		=> 4,
				'condition_2_trials'	=> 6,
				'condition_2_value'		=> 30,
				'condition_3_id'		=> 18,
				'condition_3_trials'	=> 3,
				'condition_3_value'		=> 25,
				'experience_points'		=> serialize(array(0,0,1,2,3)),
			),
			array(
				'id'					=> 5,
				'name'					=> $user->lang('WORK_5'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 1,
				'condition_1_id'		=> 1,
				'condition_1_trials'	=> 5,
				'condition_1_value'		=> 30,
				'condition_2_id'		=> 4,
				'condition_2_trials'	=> 6,
				'condition_2_value'		=> 35,
				'condition_3_id'		=> 18,
				'condition_3_trials'	=> 4,
				'condition_3_value'		=> 30,
				'experience_points'		=> serialize(array(0,1,2,3,4)),
			),
			array(
				'id'					=> 6,
				'name'					=> $user->lang('WORK_6'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 1,
				'condition_1_id'		=> 1,
				'condition_1_trials'	=> 4,
				'condition_1_value'		=> 35,
				'condition_2_id'		=> 4,
				'condition_2_trials'	=> 6,
				'condition_2_value'		=> 40,
				'condition_3_id'		=> 18,
				'condition_3_trials'	=> 5,
				'condition_3_value'		=> 35,
				'experience_points'		=> serialize(array(1,2,3,4,5)),
			),
			array(
				'id'					=> 7,
				'name'					=> $user->lang('WORK_7'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 2,
				'condition_1_id'		=> 17,
				'condition_1_trials'	=> 15,
				'condition_1_value'		=> 20,
				'condition_2_id'		=> 0,
				'condition_2_trials'	=> 0,
				'condition_2_value'		=> 0,
				'condition_3_id'		=> 0,
				'condition_3_trials'	=> 0,
				'condition_3_value'		=> 0,
				'experience_points'		=> serialize(array(0,0,0,1,2)),
			),
			array(
				'id'					=> 8,
				'name'					=> $user->lang('WORK_8'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 2,
				'condition_1_id'		=> 17,
				'condition_1_trials'	=> 15,
				'condition_1_value'		=> 25,
				'condition_2_id'		=> 0,
				'condition_2_trials'	=> 0,
				'condition_2_value'		=> 0,
				'condition_3_id'		=> 0,
				'condition_3_trials'	=> 0,
				'condition_3_value'		=> 0,
				'experience_points'		=> serialize(array(0,0,1,2,3)),
			),
			array(
				'id'					=> 9,
				'name'					=> $user->lang('WORK_9'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 2,
				'condition_1_id'		=> 17,
				'condition_1_trials'	=> 10,
				'condition_1_value'		=> 30,
				'condition_2_id'		=> 6,
				'condition_2_trials'	=> 5,
				'condition_2_value'		=> 20,
				'condition_3_id'		=> 0,
				'condition_3_trials'	=> 0,
				'condition_3_value'		=> 0,
				'experience_points'		=> serialize(array(1,2,3,4,5)),
			),
			array(
				'id'					=> 10,
				'name'					=> $user->lang('WORK_10'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 2,
				'condition_1_id'		=> 17,
				'condition_1_trials'	=> 8,
				'condition_1_value'		=> 25,
				'condition_2_id'		=> 16,
				'condition_2_trials'	=> 5,
				'condition_2_value'		=> 15,
				'condition_3_id'		=> 15,
				'condition_3_trials'	=> 3,
				'condition_3_value'		=> 20,
				'experience_points'		=> serialize(array(1,2,3,4,5)),
			),
			array(
				'id'					=> 11,
				'name'					=> $user->lang('WORK_11'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 2,
				'condition_1_id'		=> 17,
				'condition_1_trials'	=> 7,
				'condition_1_value'		=> 30,
				'condition_2_id'		=> 16,
				'condition_2_trials'	=> 4,
				'condition_2_value'		=> 20,
				'condition_3_id'		=> 15,
				'condition_3_trials'	=> 5,
				'condition_3_value'		=> 25,
				'experience_points'		=> serialize(array(2,3,4,5,6)),
			),
			array(
				'id'					=> 12,
				'name'					=> $user->lang('WORK_12'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 2,
				'condition_1_id'		=> 17,
				'condition_1_trials'	=> 6,
				'condition_1_value'		=> 35,
				'condition_2_id'		=> 16,
				'condition_2_trials'	=> 3,
				'condition_2_value'		=> 25,
				'condition_3_id'		=> 15,
				'condition_3_trials'	=> 6,
				'condition_3_value'		=> 30,
				'experience_points'		=> serialize(array(3,4,5,6,7)),
			),
			array(
				'id'					=> 13,
				'name'					=> $user->lang('WORK_13'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 3,
				'condition_1_id'		=> 8,
				'condition_1_trials'	=> 12,
				'condition_1_value'		=> 20,
				'condition_2_id'		=> 11,
				'condition_2_trials'	=> 3,
				'condition_2_value'		=> 20,
				'condition_3_id'		=> 0,
				'condition_3_trials'	=> 0,
				'condition_3_value'		=> 0,
				'experience_points'		=> serialize(array(0,0,0,1,2)),
			),
			array(
				'id'					=> 14,
				'name'					=> $user->lang('WORK_14'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 3,
				'condition_1_id'		=> 8,
				'condition_1_trials'	=> 9,
				'condition_1_value'		=> 25,
				'condition_2_id'		=> 11,
				'condition_2_trials'	=> 6,
				'condition_2_value'		=> 25,
				'condition_3_id'		=> 0,
				'condition_3_trials'	=> 0,
				'condition_3_value'		=> 0,
				'experience_points'		=> serialize(array(0,0,1,2,3)),
			),
			array(
				'id'					=> 15,
				'name'					=> $user->lang('WORK_15'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 3,
				'condition_1_id'		=> 8,
				'condition_1_trials'	=> 6,
				'condition_1_value'		=> 30,
				'condition_2_id'		=> 11,
				'condition_2_trials'	=> 9,
				'condition_2_value'		=> 35,
				'condition_3_id'		=> 0,
				'condition_3_trials'	=> 0,
				'condition_3_value'		=> 0,
				'experience_points'		=> serialize(array(1,2,3,4,5)),
			),
			array(
				'id'					=> 16,
				'name'					=> $user->lang('WORK_16'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 3,
				'condition_1_id'		=> 8,
				'condition_1_trials'	=> 5,
				'condition_1_value'		=> 20,
				'condition_2_id'		=> 11,
				'condition_2_trials'	=> 5,
				'condition_2_value'		=> 25,
				'condition_3_id'		=> 20,
				'condition_3_trials'	=> 5,
				'condition_3_value'		=> 20,
				'experience_points'		=> serialize(array(0,1,2,3,4)),
			),
			array(
				'id'					=> 17,
				'name'					=> $user->lang('WORK_17'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 3,
				'condition_1_id'		=> 8,
				'condition_1_trials'	=> 3,
				'condition_1_value'		=> 25,
				'condition_2_id'		=> 6,
				'condition_2_trials'	=> 7,
				'condition_2_value'		=> 25,
				'condition_3_id'		=> 20,
				'condition_3_trials'	=> 5,
				'condition_3_value'		=> 30,
				'experience_points'		=> serialize(array(1,2,3,4,5)),
			),
			array(
				'id'					=> 18,
				'name'					=> $user->lang('WORK_18'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 3,
				'condition_1_id'		=> 11,
				'condition_1_trials'	=> 5,
				'condition_1_value'		=> 30,
				'condition_2_id'		=> 20,
				'condition_2_trials'	=> 5,
				'condition_2_value'		=> 35,
				'condition_3_id'		=> 23,
				'condition_3_trials'	=> 5,
				'condition_3_value'		=> 25,
				'experience_points'		=> serialize(array(3,4,5,6,7)),
			),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_works', $works);
	}

	public function insert_work_outputs()
	{
		$output = array(
			/**
			 * work id = 1
			 */
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
			/**
			 * work id = 2
			 */
			array(
				'id'					=> 6,
				'work_id'				=> 2,
				'success_threshold'		=> 1,
				'output_id'				=> 1,
				'output_value'			=> 4,
			),
			array(
				'id'					=> 7,
				'work_id'				=> 2,
				'success_threshold'		=> 2,
				'output_id'				=> 1,
				'output_value'			=> 6,
			),
			array(
				'id'					=> 8,
				'work_id'				=> 2,
				'success_threshold'		=> 3,
				'output_id'				=> 1,
				'output_value'			=> 8,
			),
			array(
				'id'					=> 9,
				'work_id'				=> 2,
				'success_threshold'		=> 4,
				'output_id'				=> 1,
				'output_value'			=> 10,
			),
			array(
				'id'					=> 10,
				'work_id'				=> 2,
				'success_threshold'		=> 5,
				'output_id'				=> 1,
				'output_value'			=> 12,
			),
			/**
			 * work id = 3
			 */
			array(
				'id'					=> 11,
				'work_id'				=> 3,
				'success_threshold'		=> 1,
				'output_id'				=> 1,
				'output_value'			=> 6,
			),
			array(
				'id'					=> 12,
				'work_id'				=> 3,
				'success_threshold'		=> 2,
				'output_id'				=> 1,
				'output_value'			=> 8,
			),
			array(
				'id'					=> 13,
				'work_id'				=> 3,
				'success_threshold'		=> 3,
				'output_id'				=> 1,
				'output_value'			=> 10,
			),
			array(
				'id'					=> 14,
				'work_id'				=> 3,
				'success_threshold'		=> 4,
				'output_id'				=> 1,
				'output_value'			=> 12,
			),
			array(
				'id'					=> 15,
				'work_id'				=> 3,
				'success_threshold'		=> 5,
				'output_id'				=> 1,
				'output_value'			=> 14,
			),
			/**
			 * work id = 4
			 */
			array(
				'id'					=> 16,
				'work_id'				=> 4,
				'success_threshold'		=> 1,
				'output_id'				=> 1,
				'output_value'			=> 4,
			),
			array(
				'id'					=> 17,
				'work_id'				=> 4,
				'success_threshold'		=> 2,
				'output_id'				=> 1,
				'output_value'			=> 6,
			),
			array(
				'id'					=> 18,
				'work_id'				=> 4,
				'success_threshold'		=> 3,
				'output_id'				=> 1,
				'output_value'			=> 8,
			),
			array(
				'id'					=> 19,
				'work_id'				=> 4,
				'success_threshold'		=> 4,
				'output_id'				=> 1,
				'output_value'			=> 10,
			),
			array(
				'id'					=> 20,
				'work_id'				=> 4,
				'success_threshold'		=> 5,
				'output_id'				=> 1,
				'output_value'			=> 12,
			),
			/**
			 * work id = 5
			 */
			array(
				'id'					=> 21,
				'work_id'				=> 5,
				'success_threshold'		=> 1,
				'output_id'				=> 1,
				'output_value'			=> 6,
			),
			array(
				'id'					=> 22,
				'work_id'				=> 5,
				'success_threshold'		=> 2,
				'output_id'				=> 1,
				'output_value'			=> 8,
			),
			array(
				'id'					=> 23,
				'work_id'				=> 5,
				'success_threshold'		=> 3,
				'output_id'				=> 1,
				'output_value'			=> 10,
			),
			array(
				'id'					=> 24,
				'work_id'				=> 5,
				'success_threshold'		=> 4,
				'output_id'				=> 1,
				'output_value'			=> 12,
			),
			array(
				'id'					=> 25,
				'work_id'				=> 5,
				'success_threshold'		=> 5,
				'output_id'				=> 1,
				'output_value'			=> 14,
			),
			/**
			 * work id = 6
			 */
			array(
				'id'					=> 26,
				'work_id'				=> 6,
				'success_threshold'		=> 1,
				'output_id'				=> 1,
				'output_value'			=> 8,
			),
			array(
				'id'					=> 27,
				'work_id'				=> 6,
				'success_threshold'		=> 2,
				'output_id'				=> 1,
				'output_value'			=> 10,
			),
			array(
				'id'					=> 28,
				'work_id'				=> 6,
				'success_threshold'		=> 3,
				'output_id'				=> 1,
				'output_value'			=> 12,
			),
			array(
				'id'					=> 29,
				'work_id'				=> 6,
				'success_threshold'		=> 4,
				'output_id'				=> 1,
				'output_value'			=> 14,
			),
			array(
				'id'					=> 30,
				'work_id'				=> 6,
				'success_threshold'		=> 5,
				'output_id'				=> 1,
				'output_value'			=> 16,
			),
			/**
			 * work id = 7
			 */
			array(
				'id'					=> 31,
				'work_id'				=> 7,
				'success_threshold'		=> 1,
				'output_id'				=> 1,
				'output_value'			=> 2,
			),
			array(
				'id'					=> 32,
				'work_id'				=> 7,
				'success_threshold'		=> 2,
				'output_id'				=> 1,
				'output_value'			=> 4,
			),
			array(
				'id'					=> 33,
				'work_id'				=> 7,
				'success_threshold'		=> 3,
				'output_id'				=> 1,
				'output_value'			=> 6,
			),
			array(
				'id'					=> 34,
				'work_id'				=> 7,
				'success_threshold'		=> 4,
				'output_id'				=> 1,
				'output_value'			=> 8,
			),
			array(
				'id'					=> 35,
				'work_id'				=> 7,
				'success_threshold'		=> 5,
				'output_id'				=> 1,
				'output_value'			=> 10,
			),
			/**
			 * work id = 8
			 */
			array(
				'id'					=> 36,
				'work_id'				=> 8,
				'success_threshold'		=> 1,
				'output_id'				=> 1,
				'output_value'			=> 4,
			),
			array(
				'id'					=> 37,
				'work_id'				=> 8,
				'success_threshold'		=> 2,
				'output_id'				=> 1,
				'output_value'			=> 6,
			),
			array(
				'id'					=> 38,
				'work_id'				=> 8,
				'success_threshold'		=> 3,
				'output_id'				=> 1,
				'output_value'			=> 8,
			),
			array(
				'id'					=> 39,
				'work_id'				=> 8,
				'success_threshold'		=> 4,
				'output_id'				=> 1,
				'output_value'			=> 10,
			),
			array(
				'id'					=> 40,
				'work_id'				=> 8,
				'success_threshold'		=> 5,
				'output_id'				=> 1,
				'output_value'			=> 12,
			),
			/**
			 * work id = 9
			 */
			array(
				'id'					=> 41,
				'work_id'				=> 9,
				'success_threshold'		=> 1,
				'output_id'				=> 1,
				'output_value'			=> 6,
			),
			array(
				'id'					=> 42,
				'work_id'				=> 9,
				'success_threshold'		=> 2,
				'output_id'				=> 1,
				'output_value'			=> 8,
			),
			array(
				'id'					=> 43,
				'work_id'				=> 9,
				'success_threshold'		=> 3,
				'output_id'				=> 1,
				'output_value'			=> 10,
			),
			array(
				'id'					=> 44,
				'work_id'				=> 9,
				'success_threshold'		=> 4,
				'output_id'				=> 1,
				'output_value'			=> 12,
			),
			array(
				'id'					=> 45,
				'work_id'				=> 9,
				'success_threshold'		=> 5,
				'output_id'				=> 1,
				'output_value'			=> 14,
			),
			/**
			 * work id = 10
			 */
			array(
				'id'					=> 46,
				'work_id'				=> 10,
				'success_threshold'		=> 1,
				'output_id'				=> 1,
				'output_value'			=> 6,
			),
			array(
				'id'					=> 47,
				'work_id'				=> 10,
				'success_threshold'		=> 2,
				'output_id'				=> 1,
				'output_value'			=> 8,
			),
			array(
				'id'					=> 48,
				'work_id'				=> 10,
				'success_threshold'		=> 3,
				'output_id'				=> 1,
				'output_value'			=> 10,
			),
			array(
				'id'					=> 49,
				'work_id'				=> 10,
				'success_threshold'		=> 4,
				'output_id'				=> 1,
				'output_value'			=> 12,
			),
			array(
				'id'					=> 50,
				'work_id'				=> 10,
				'success_threshold'		=> 5,
				'output_id'				=> 1,
				'output_value'			=> 14,
			),
			/**
			 * work id = 11
			 */
			array(
				'id'					=> 51,
				'work_id'				=> 11,
				'success_threshold'		=> 1,
				'output_id'				=> 1,
				'output_value'			=> 8,
			),
			array(
				'id'					=> 52,
				'work_id'				=> 11,
				'success_threshold'		=> 2,
				'output_id'				=> 1,
				'output_value'			=> 10,
			),
			array(
				'id'					=> 53,
				'work_id'				=> 11,
				'success_threshold'		=> 3,
				'output_id'				=> 1,
				'output_value'			=> 12,
			),
			array(
				'id'					=> 54,
				'work_id'				=> 11,
				'success_threshold'		=> 4,
				'output_id'				=> 1,
				'output_value'			=> 14,
			),
			array(
				'id'					=> 55,
				'work_id'				=> 11,
				'success_threshold'		=> 5,
				'output_id'				=> 1,
				'output_value'			=> 16,
			),
			/**
			 * work id = 12
			 */
			array(
				'id'					=> 56,
				'work_id'				=> 12,
				'success_threshold'		=> 1,
				'output_id'				=> 1,
				'output_value'			=> 10,
			),
			array(
				'id'					=> 57,
				'work_id'				=> 12,
				'success_threshold'		=> 2,
				'output_id'				=> 1,
				'output_value'			=> 12,
			),
			array(
				'id'					=> 58,
				'work_id'				=> 12,
				'success_threshold'		=> 3,
				'output_id'				=> 1,
				'output_value'			=> 14,
			),
			array(
				'id'					=> 59,
				'work_id'				=> 12,
				'success_threshold'		=> 4,
				'output_id'				=> 1,
				'output_value'			=> 16,
			),
			array(
				'id'					=> 60,
				'work_id'				=> 12,
				'success_threshold'		=> 5,
				'output_id'				=> 1,
				'output_value'			=> 18,
			),
			/**
			 * work id = 13
			 */
			array(
				'id'					=> 61,
				'work_id'				=> 13,
				'success_threshold'		=> 1,
				'output_id'				=> 1,
				'output_value'			=> 2,
			),
			array(
				'id'					=> 62,
				'work_id'				=> 13,
				'success_threshold'		=> 2,
				'output_id'				=> 1,
				'output_value'			=> 4,
			),
			array(
				'id'					=> 63,
				'work_id'				=> 13,
				'success_threshold'		=> 3,
				'output_id'				=> 1,
				'output_value'			=> 6,
			),
			array(
				'id'					=> 64,
				'work_id'				=> 13,
				'success_threshold'		=> 4,
				'output_id'				=> 1,
				'output_value'			=> 8,
			),
			array(
				'id'					=> 65,
				'work_id'				=> 13,
				'success_threshold'		=> 5,
				'output_id'				=> 1,
				'output_value'			=> 10,
			),
			/**
			 * work id = 14
			 */
			array(
				'id'					=> 66,
				'work_id'				=> 14,
				'success_threshold'		=> 1,
				'output_id'				=> 1,
				'output_value'			=> 4,
			),
			array(
				'id'					=> 67,
				'work_id'				=> 14,
				'success_threshold'		=> 2,
				'output_id'				=> 1,
				'output_value'			=> 6,
			),
			array(
				'id'					=> 68,
				'work_id'				=> 14,
				'success_threshold'		=> 3,
				'output_id'				=> 1,
				'output_value'			=> 8,
			),
			array(
				'id'					=> 69,
				'work_id'				=> 14,
				'success_threshold'		=> 4,
				'output_id'				=> 1,
				'output_value'			=> 10,
			),
			array(
				'id'					=> 70,
				'work_id'				=> 14,
				'success_threshold'		=> 5,
				'output_id'				=> 1,
				'output_value'			=> 12,
			),
			/**
			 * work id = 15
			 */
			array(
				'id'					=> 71,
				'work_id'				=> 15,
				'success_threshold'		=> 1,
				'output_id'				=> 1,
				'output_value'			=> 8,
			),
			array(
				'id'					=> 72,
				'work_id'				=> 15,
				'success_threshold'		=> 2,
				'output_id'				=> 1,
				'output_value'			=> 10,
			),
			array(
				'id'					=> 73,
				'work_id'				=> 15,
				'success_threshold'		=> 3,
				'output_id'				=> 1,
				'output_value'			=> 12,
			),
			array(
				'id'					=> 74,
				'work_id'				=> 15,
				'success_threshold'		=> 4,
				'output_id'				=> 1,
				'output_value'			=> 14,
			),
			array(
				'id'					=> 75,
				'work_id'				=> 15,
				'success_threshold'		=> 5,
				'output_id'				=> 1,
				'output_value'			=> 16,
			),
			/**
			 * work id = 16
			 */
			array(
				'id'					=> 76,
				'work_id'				=> 16,
				'success_threshold'		=> 1,
				'output_id'				=> 1,
				'output_value'			=> 4,
			),
			array(
				'id'					=> 77,
				'work_id'				=> 16,
				'success_threshold'		=> 2,
				'output_id'				=> 1,
				'output_value'			=> 6,
			),
			array(
				'id'					=> 78,
				'work_id'				=> 16,
				'success_threshold'		=> 3,
				'output_id'				=> 1,
				'output_value'			=> 8,
			),
			array(
				'id'					=> 79,
				'work_id'				=> 16,
				'success_threshold'		=> 4,
				'output_id'				=> 1,
				'output_value'			=> 10,
			),
			array(
				'id'					=> 80,
				'work_id'				=> 16,
				'success_threshold'		=> 5,
				'output_id'				=> 1,
				'output_value'			=> 12,
			),
			/**
			 * work id = 17
			 */
			array(
				'id'					=> 81,
				'work_id'				=> 17,
				'success_threshold'		=> 1,
				'output_id'				=> 1,
				'output_value'			=> 6,
			),
			array(
				'id'					=> 82,
				'work_id'				=> 17,
				'success_threshold'		=> 2,
				'output_id'				=> 1,
				'output_value'			=> 8,
			),
			array(
				'id'					=> 83,
				'work_id'				=> 17,
				'success_threshold'		=> 3,
				'output_id'				=> 1,
				'output_value'			=> 10,
			),
			array(
				'id'					=> 84,
				'work_id'				=> 17,
				'success_threshold'		=> 4,
				'output_id'				=> 1,
				'output_value'			=> 12,
			),
			array(
				'id'					=> 85,
				'work_id'				=> 17,
				'success_threshold'		=> 5,
				'output_id'				=> 1,
				'output_value'			=> 14,
			),
			/**
			 * work id = 18
			 */
			array(
				'id'					=> 86,
				'work_id'				=> 18,
				'success_threshold'		=> 1,
				'output_id'				=> 1,
				'output_value'			=> 10,
			),
			array(
				'id'					=> 87,
				'work_id'				=> 18,
				'success_threshold'		=> 2,
				'output_id'				=> 1,
				'output_value'			=> 12,
			),
			array(
				'id'					=> 88,
				'work_id'				=> 18,
				'success_threshold'		=> 3,
				'output_id'				=> 1,
				'output_value'			=> 14,
			),
			array(
				'id'					=> 89,
				'work_id'				=> 18,
				'success_threshold'		=> 4,
				'output_id'				=> 1,
				'output_value'			=> 16,
			),
			array(
				'id'					=> 90,
				'work_id'				=> 18,
				'success_threshold'		=> 5,
				'output_id'				=> 1,
				'output_value'			=> 18,
			),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_work_outputs', $output);
	}

	public function insert_roads()
	{
		/** @var \phpbb\user $user */
		global $user;

		$provinces = array(
			array('id' => 20, 'title' => $user->lang('VARNOGRAD').' - '.$user->lang('ALATYR') , 'blocked' => 0, 'type' => 2, 'prvnce_id' => 3),
			array('id' => 21, 'title' => $user->lang('JEGENSK').' - '.$user->lang('ALATYR') , 'blocked' => 0, 'type' => 2, 'prvnce_id' => 3),
			array('id' => 22, 'title' => $user->lang('VARNOGRAD').' - '.$user->lang('JEGENSK') , 'blocked' => 0, 'type' => 2, 'prvnce_id' => 3),
			array('id' => 23, 'title' => $user->lang('PKD_74').' - '.$user->lang('JEGENSK') , 'blocked' => 0, 'type' => 1, 'prvnce_id' => 3),
			array('id' => 24, 'title' => $user->lang('GRUSHEVSK').' - '.$user->lang('PKD_74') , 'blocked' => 0, 'type' => 2, 'prvnce_id' => 3),
			array('id' => 25, 'title' => $user->lang('GRUSHEVSK').' - '.$user->lang('NOBRI') , 'blocked' => 0, 'type' => 3, 'prvnce_id' => 3),
			array('id' => 26, 'title' => $user->lang('KUBISHEVSK').' - '.$user->lang('NOBRI') , 'blocked' => 0, 'type' => 3, 'prvnce_id' => 3),
			array('id' => 27, 'title' => $user->lang('ALATYR').' - '.$user->lang('NOBRI') , 'blocked' => 0, 'type' => 2, 'prvnce_id' => 3),
			array('id' => 28, 'title' => $user->lang('ALATYR').' - '.$user->lang('TURAV') , 'blocked' => 0, 'type' => 1, 'prvnce_id' => 3),
			array('id' => 29, 'title' => $user->lang('KUBISHEVSK').' - '.$user->lang('TURAV') , 'blocked' => 0, 'type' => 1, 'prvnce_id' => 3),
			array('id' => 30, 'title' => $user->lang('KUBISHEVSK').' - '.$user->lang('SMTU_567_C') , 'blocked' => 0, 'type' => 1, 'prvnce_id' => 3),
			array('id' => 31, 'title' => $user->lang('ASTRAKAN').' - '.$user->lang('SMTU_567_C') , 'blocked' => 0, 'type' => 1, 'prvnce_id' => 3),
			array('id' => 32, 'title' => $user->lang('ASTRAKAN').' - '.$user->lang('NOBRI') , 'blocked' => 0, 'type' => 2, 'prvnce_id' => 3),
			array('id' => 33, 'title' => $user->lang('ASTRAKAN').' - '.$user->lang('KIRGANOV') , 'blocked' => 0, 'type' => 1, 'prvnce_id' => 3),
			array('id' => 34, 'title' => $user->lang('GRUSHEVSK').' - '.$user->lang('KIRGANOV') , 'blocked' => 0, 'type' => 1, 'prvnce_id' => 3),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_roads', $provinces);
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
				$this->table_prefix . 'consim_markers',
				$this->table_prefix . 'consim_roads',
			),
		);
	}
}
