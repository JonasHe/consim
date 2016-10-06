<?php

/**
 *
 * @package ConSim for phpBB3.1
 * @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
 * @copyright (c) 2016 Jonas Heitmann (kommodoree@googlemail.com)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace consim\core\migrations\v10x;

class m4_province_3 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\consim\core\migrations\v10x\m3_work_data');
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
			array('custom', array(array($this, 'insert_routes'))),
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

}
