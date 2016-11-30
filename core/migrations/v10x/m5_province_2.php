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

class m5_province_2 extends \phpbb\db\migration\migration
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
			array('id' => 12, 'name' => $user->lang('KRASNOGORSK'),
			      'description' => $user->lang('KRASNOGORSK_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 4, 'province_id' => 2, 'x' => 691, 'y' => 138, 'map_name' => 'mainMap'),
			array('id' => 13, 'name' => $user->lang('ISCHEWSK'),
			      'description' => $user->lang('ISCHEWSK_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 2, 'province_id' => 2, 'x' => 764, 'y' => 126, 'map_name' => 'mainMap'),
			array('id' => 14, 'name' => $user->lang('SEWERMORSK'),
			      'description' => $user->lang('SEWERMORSK_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 2, 'province_id' => 2, 'x' => 770, 'y' => 202, 'map_name' => 'mainMap'),
			array('id' => 15, 'name' => $user->lang('SOLIKAMSK'),
			      'description' => $user->lang('SOLIKAMSK_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 5, 'province_id' => 2, 'x' => 895, 'y' => 120, 'map_name' => 'mainMap'),
			array('id' => 16, 'name' => $user->lang('LMPTA'),
			      'description' => $user->lang('LMPTA_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 6, 'province_id' => 2, 'x' => 945, 'y' => 65, 'map_name' => 'mainMap'),
			array('id' => 17, 'name' => $user->lang('KOSLOWKA'),
			      'description' => $user->lang('KOSLOWKA_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 1, 'province_id' => 2, 'x' => 765, 'y' => 78, 'map_name' => 'mainMap'),
			array('id' => 18, 'name' => $user->lang('BBSOK'),
			      'description' => $user->lang('BBSOK_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 6, 'province_id' => 2, 'x' => 606, 'y' => 80, 'map_name' => 'mainMap'),
			array('id' => 19, 'name' => $user->lang('GKBLF_TAIP'),
			      'description' => $user->lang('GKBLF_TAIP_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 6, 'province_id' => 2, 'x' => 692, 'y' => 80, 'map_name' => 'mainMap'),
			array('id' => 20, 'name' => $user->lang('SPA_BRW'),
			      'description' => $user->lang('SPA_BRW_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 6, 'province_id' => 2, 'x' => 568, 'y' => 150, 'map_name' => 'mainMap'),
			array('id' => 21, 'name' => $user->lang('PROLETARSK'),
			      'description' => $user->lang('PROLETARSK_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 5, 'province_id' => 2, 'x' => 622, 'y' => 164, 'map_name' => 'mainMap'),
			array('id' => 22, 'name' => $user->lang('MICHAILOWSK'),
			      'description' => $user->lang('MICHAILOWSK_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 1, 'province_id' => 2, 'x' => 589, 'y' => 269, 'map_name' => 'mainMap'),
			array('id' => 23, 'name' => $user->lang('RYZHOM'),
			      'description' => $user->lang('RYZHOM_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 2, 'province_id' => 2, 'x' => 571, 'y' => 218, 'map_name' => 'mainMap'),
			array('id' => 24, 'name' => $user->lang('KIROVOGRAD'),
			      'description' => $user->lang('KIROVOGRAD_DESC'),
			      'image' => 'locations/placeholder.jpg', 'type_id' => 2, 'province_id' => 2, 'x' => 554, 'y' => 124, 'map_name' => 'mainMap'),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_locations', $locations);
	}

	public function insert_routes()
	{
		$routes = array(
			// Streets across the border of the province
			array('id' => 17, 'start_location_id' => 6, 'end_location_id' => 15, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 18, 'start_location_id' => 5, 'end_location_id' => 14, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 19, 'start_location_id' => 11, 'end_location_id' => 22, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			// Streets within the border of the province
			array('id' => 20, 'start_location_id' => 12, 'end_location_id' => 18, 'time' => 360,
			      'blocked' => 0, 'type' => 3, 'prvnce_id' => 2),
			array('id' => 21, 'start_location_id' => 12, 'end_location_id' => 13, 'time' => 360,
			      'blocked' => 0, 'type' => 3, 'prvnce_id' => 2),
			array('id' => 22, 'start_location_id' => 12, 'end_location_id' => 14, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 23, 'start_location_id' => 12, 'end_location_id' => 21, 'time' => 360,
			      'blocked' => 0, 'type' => 3, 'prvnce_id' => 2),
			array('id' => 24, 'start_location_id' => 12, 'end_location_id' => 19, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 25, 'start_location_id' => 13, 'end_location_id' => 14, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 26, 'start_location_id' => 13, 'end_location_id' => 17, 'time' => 360,
			      'blocked' => 0, 'type' => 1, 'prvnce_id' => 2),
			array('id' => 27, 'start_location_id' => 13, 'end_location_id' => 15, 'time' => 360,
			      'blocked' => 0, 'type' => 3, 'prvnce_id' => 2),
			array('id' => 28, 'start_location_id' => 14, 'end_location_id' => 15, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 29, 'start_location_id' => 14, 'end_location_id' => 21, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 30, 'start_location_id' => 15, 'end_location_id' => 16, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 31, 'start_location_id' => 16, 'end_location_id' => 17, 'time' => 360,
			      'blocked' => 0, 'type' => 1, 'prvnce_id' => 2),
			array('id' => 32, 'start_location_id' => 17, 'end_location_id' => 19, 'time' => 360,
			      'blocked' => 0, 'type' => 1, 'prvnce_id' => 2),
			array('id' => 33, 'start_location_id' => 18, 'end_location_id' => 19, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 34, 'start_location_id' => 18, 'end_location_id' => 20, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 35, 'start_location_id' => 18, 'end_location_id' => 24, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 36, 'start_location_id' => 20, 'end_location_id' => 21, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 37, 'start_location_id' => 20, 'end_location_id' => 23, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 38, 'start_location_id' => 20, 'end_location_id' => 24, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 39, 'start_location_id' => 21, 'end_location_id' => 22, 'time' => 360,
			      'blocked' => 0, 'type' => 1, 'prvnce_id' => 2),
			array('id' => 40, 'start_location_id' => 21, 'end_location_id' => 23, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
			array('id' => 41, 'start_location_id' => 22, 'end_location_id' => 23, 'time' => 360,
			      'blocked' => 0, 'type' => 1, 'prvnce_id' => 2),
			array('id' => 42, 'start_location_id' => 23, 'end_location_id' => 24, 'time' => 360,
			      'blocked' => 0, 'type' => 2, 'prvnce_id' => 2),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_routes', $routes);
	}

}
