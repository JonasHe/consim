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
			array('custom', array(array($this, 'insert_roads'))),
		);
	}

	public function insert_locations()
	{
		/** @var \phpbb\user $user */
		global $user;

		$locations = array(
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_locations', $locations);
	}

	public function insert_routes()
	{
		$routes = array(
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_routes', $routes);
	}

	public function insert_roads()
	{
		/** @var \phpbb\user $user */
		global $user;

		$provinces = array(
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_roads', $provinces);
	}

}
