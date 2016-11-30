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

class m4_province_1 extends \phpbb\db\migration\migration
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
		);
	}

	public function insert_locations()
	{
		/** @var \phpbb\user $user */
		global $user;

		$locations = array(
			array('id' => 25, 'name' => $user->lang('CHAROWSK'),
			      'description' => $user->lang('CHAROWSK_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 3, 'province_id' => 1, 'x' => 445, 'y' => 103, 'map_name' => 'mainMap'),
			array('id' => 26, 'name' => $user->lang('MOROSOWOGRAD'),
			      'description' => $user->lang('MOROSOWOGRAD_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 2, 'province_id' => 1, 'x' => 396, 'y' => 70, 'map_name' => 'mainMap'),
			array('id' => 27, 'name' => $user->lang('USSINSK'),
			      'description' => $user->lang('USSINSK_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 2, 'province_id' => 1, 'x' => 520, 'y' => 159, 'map_name' => 'mainMap'),
			array('id' => 28, 'name' => $user->lang('ROSHDESTVENSKOJE'),
			      'description' => $user->lang('ROSHDESTVENSKOJE_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 5, 'province_id' => 1, 'x' => 357, 'y' => 153, 'map_name' => 'mainMap'),
			array('id' => 29, 'name' => $user->lang('TUPRUNKA'),
			      'description' => $user->lang('TUPRUNKA_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 5, 'province_id' => 1, 'x' => 374, 'y' => 44, 'map_name' => 'mainMap'),
			array('id' => 30, 'name' => $user->lang('LIWNIJOL'),
			      'description' => $user->lang('LIWNIJOL_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 1, 'province_id' => 1, 'x' => 523, 'y' => 127, 'map_name' => 'mainMap'),
			array('id' => 31, 'name' => $user->lang('JAROWOJE'),
			      'description' => $user->lang('JAROWOJE_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 1, 'province_id' => 1, 'x' => 439, 'y' => 55, 'map_name' => 'mainMap'),
			array('id' => 32, 'name' => $user->lang('KEDROWY'),
			      'description' => $user->lang('KEDROWY_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 1, 'province_id' => 1, 'x' => 442, 'y' => 195, 'map_name' => 'mainMap'),
			array('id' => 33, 'name' => $user->lang('MALMIRSHK'),
			      'description' => $user->lang('MALMIRSHK_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 1, 'province_id' => 1, 'x' => 333, 'y' => 125, 'map_name' => 'mainMap'),
			array('id' => 34, 'name' => $user->lang('GKA_IGGSG'),
			      'description' => $user->lang('GKA_IGGSG_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 6, 'province_id' => 1, 'x' => 385, 'y' => 109, 'map_name' => 'mainMap'),
			array('id' => 35, 'name' => $user->lang('NSG_71'),
			      'description' => $user->lang('NSG_71_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 6, 'province_id' => 1, 'x' => 415, 'y' => 145, 'map_name' => 'mainMap'),
			array('id' => 36, 'name' => $user->lang('RK_BATS'),
			      'description' => $user->lang('RK_BATS_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 6, 'province_id' => 1, 'x' => 543, 'y' => 69, 'map_name' => 'mainMap'),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_locations', $locations);
	}

	public function insert_routes()
	{
		$routes = array(
			// Streets across the border of a province
			array('id' => 43, 'start_location_id' => 24, 'end_location_id' => 30, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 44, 'start_location_id' => 23, 'end_location_id' => 27, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			// Streets within the border of a province
			array('id' => 45, 'start_location_id' => 25, 'end_location_id' => 36, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 46, 'start_location_id' => 25, 'end_location_id' => 30, 'time' => 360,
			      'blocked' => 0, 'type' => 3, 'prvnce_id' => 2),
			array('id' => 47, 'start_location_id' => 25, 'end_location_id' => 27, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 48, 'start_location_id' => 25, 'end_location_id' => 26, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 49, 'start_location_id' => 25, 'end_location_id' => 29, 'time' => 360,
			      'blocked' => 0, 'type' => 3, 'prvnce_id' => 2),
			array('id' => 50, 'start_location_id' => 36, 'end_location_id' => 30, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 51, 'start_location_id' => 36, 'end_location_id' => 31, 'time' => 360,
			      'blocked' => 0, 'type' => 1, 'prvnce_id' => 2),
			array('id' => 52, 'start_location_id' => 30, 'end_location_id' => 27, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 53, 'start_location_id' => 27, 'end_location_id' => 32, 'time' => 360,
			      'blocked' => 0, 'type' => 1, 'prvnce_id' => 2),
			array('id' => 54, 'start_location_id' => 32, 'end_location_id' => 28, 'time' => 360,
			      'blocked' => 0, 'type' => 1, 'prvnce_id' => 2),
			array('id' => 55, 'start_location_id' => 32, 'end_location_id' => 35, 'time' => 360,
			      'blocked' => 0, 'type' => 1, 'prvnce_id' => 2),
			array('id' => 56, 'start_location_id' => 35, 'end_location_id' => 34, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 57, 'start_location_id' => 35, 'end_location_id' => 28, 'time' => 360,
			      'blocked' => 0, 'type' => 1, 'prvnce_id' => 2),
			array('id' => 58, 'start_location_id' => 28, 'end_location_id' => 33, 'time' => 360,
			      'blocked' => 0, 'type' => 1, 'prvnce_id' => 2),
			array('id' => 59, 'start_location_id' => 34, 'end_location_id' => 26, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 60, 'start_location_id' => 34, 'end_location_id' => 33, 'time' => 360,
			      'blocked' => 0, 'type' => 1, 'prvnce_id' => 2),
			array('id' => 61, 'start_location_id' => 33, 'end_location_id' => 29, 'time' => 360,
			      'blocked' => 0, 'type' => 1, 'prvnce_id' => 2),
			array('id' => 62, 'start_location_id' => 26, 'end_location_id' => 29, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 63, 'start_location_id' => 29, 'end_location_id' => 31, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_routes', $routes);
	}

}
