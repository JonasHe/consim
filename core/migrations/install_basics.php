<?php

/**
*
* @package ConSim for phpBB3.1
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @copyright (c) 2016 Jonas Heitmann (kommodoree@googlemail.com)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace consim\core\migrations;

class install_basics extends \phpbb\db\migration\migration
{
	var $consim_version = '0.0.1';

	public function effectively_installed()
	{
		return isset($this->config['consim_version']) && version_compare($this->config['consim_version'], $this->consim_version, '>=');
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\dev');
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
			'add_tables' => array(
				$this->table_prefix . 'consim_user'	=> array(
					'COLUMNS'		=> array(
						'user_id'				=> array('UINT:8', 0),
						'vorname'				=> array('VCHAR:255', ''),
						'nachname'				=> array('VCHAR:255', ''),
						'geschlecht_id'			=> array('UINT:8', 0),
						'geburtsland_id'		=> array('UINT:8', 0),
						'religion_id'			=> array('UINT:8', 0),
						'haarfarbe_id'			=> array('UINT:8', 0),
						'augenfarbe_id'			=> array('UINT:8', 0),
						'besondere_merkmale_id'	=> array('UINT:8', 0),
						'experience_points'		=> array('UINT:8', 0),
						'location_id'			=> array('UINT:8', 1),
						'active'				=> array('BOOL', 0),
					),
					'PRIMARY_KEY'	=> array('user_id'),
					'KEYS'			=> array(
						'location_id'	=> array('INDEX', 'location_id'),
					),
				),
				$this->table_prefix . 'consim_figure' => array(
					'COLUMNS'		=> array(
						'id'					=> array('UINT:8', 0),
						'groups'				=> array('VCHAR:255', ''),
						'value'					=> array('VCHAR:255', ''),
						'name'					=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> array('id'),
				),
				$this->table_prefix . 'consim_skills' => array(
					'COLUMNS'		=> array(
						'id'					=> array('UINT:8', 0),
						'name'					=> array('VCHAR:255', ''),
						'cat'					=> array('VCHAR:255', ''),
						'country_id'			=> array('UINT:8', 0),
					),
					'PRIMARY_KEY'	=> array('id'),
				),
				$this->table_prefix . 'consim_user_skills' => array(
					'COLUMNS'		=> array(
						'id'					=> array('UINT:8', NULL, 'auto_increment'),
						'user_id'				=> array('UINT:8', 0),
						'skill_id'				=> array('UINT:8', 0),
						'value'					=> array('UINT:8', 0),
					),
					'PRIMARY_KEY'	=> array('id'),
					'KEYS'			=> array(
						'uid'	=> array('INDEX', 'user_id'),
						'sid'	=> array('INDEX', 'skill_id'),
					),
				),
				$this->table_prefix . 'consim_items' => array(
					'COLUMNS'		=> array(
						'id'					=> array('UINT:8', 0),
						'name'					=> array('VCHAR:255', ''),
						'short_name'			=> array('VCHAR:255', ''),
						'all_user'				=> array('BOOL', 0),
					),
					'PRIMARY_KEY'	=> array('id'),
				),
				$this->table_prefix . 'consim_inventory_items' => array(
					'COLUMNS'		=> array(
						'id'					=> array('UINT:8', NULL, 'auto_increment'),
						'user_id'				=> array('UINT:8', 0),
						'item_id'				=> array('UINT:8', 0),
						'value'					=> array('UINT:8', 0),
					),
					'PRIMARY_KEY'	=> array('id'),
					'KEYS'		=> array(
						'u'		=> array('UNIQUE',  array('user_id', 'item_id')),
					),
				),
				$this->table_prefix . 'consim_actions' => array(
					'COLUMNS'		=> array(
						'id'					=> array('UINT:8', NULL, 'auto_increment'),
						'user_id'				=> array('UINT:8', 0),
						'location_id'			=> array('UINT:8', 0),
						'starttime'				=> array('TIMESTAMP', 0),
						'endtime'				=> array('TIMESTAMP', 0),
						'route_id'				=> array('UINT:8', 0),
						'work_id'				=> array('UINT:8', 0),
						'successful_trials'		=> array('TINT:1', 0),
						'status'				=> array('BOOL', 0),
					),
					'PRIMARY_KEY'	=> array('id'),
					'KEYS'			=> array(
						'starttime'		=> array('INDEX', 'starttime'),
						'endtime'		=> array('INDEX', 'endtime'),
						'route_id'		=> array('INDEX', 'route_id'),
						'work_id'		=> array('INDEX', 'work_id'),
						'status'		=> array('INDEX', 'status'),
					),
				),
			),
			'add_columns' => array(
				$this->table_prefix . 'users' => array(
						'consim_register'		=> array('BOOL', 0),
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
			// Set the current version
			array('config.add', array('consim_version', $this->consim_version)),
			array('custom', array(array($this, 'insert_consim_figure'))),
			array('custom', array(array($this, 'insert_consim_item'))),
			array('custom', array(array($this, 'insert_consim_skill'))),

			// Add an acp tab
			array('module.add', array('acp', 0, 'ACP_CONSIM')),
			array('module.add', array(
				'acp', 'ACP_CONSIM', array(
					'module_basename'	=> '\consim\core\acp\core_module',
					'modes'				=> array('news'),
				),
			)),
		);
	}

	public function insert_consim_figure()
	{
		/** @var \phpbb\user $user */
		global $user;

		$figure_data = array(
			  array('id' => '1','groups' => 'geschlecht','value' => 'm','name' => $user->lang('MANNLICH')),
			  array('id' => '2','groups' => 'geschlecht','value' => 'w','name' => $user->lang('WEIBLICH')),
			  array('id' => '3','groups' => 'geburtsland','value' => 'frt','name' => $user->lang('FRT')),
			  array('id' => '4','groups' => 'geburtsland','value' => 'bak','name' => $user->lang('VRB')),
			  array('id' => '5','groups' => 'geburtsland','value' => 'sur','name' => $user->lang('SUR')),
			  array('id' => '6','groups' => 'religion','value' => 'orthodox','name' => $user->lang('ORTHODOX')),
			  array('id' => '7','groups' => 'religion','value' => 'katholisch','name' => $user->lang('KATHOLISCH')),
			  array('id' => '8','groups' => 'religion','value' => 'muslimisch','name' => $user->lang('MUSLIMISCH')),
			  array('id' => '9','groups' => 'religion','value' => 'atheistisch','name' => $user->lang('ATHEISTISCH')),
			  array('id' => '10','groups' => 'haarfarbe','value' => 'schwarz','name' => $user->lang('SCHWARZ')),
			  array('id' => '11','groups' => 'haarfarbe','value' => 'rot','name' => $user->lang('ROT')),
			  array('id' => '12','groups' => 'haarfarbe','value' => 'hbraun','name' => $user->lang('HELLBRAUN')),
			  array('id' => '13','groups' => 'haarfarbe','value' => 'dbraun','name' => $user->lang('DUNKELBRAUN')),
			  array('id' => '14','groups' => 'haarfarbe','value' => 'blond','name' => $user->lang('BLOND')),
			  array('id' => '15','groups' => 'haarfarbe','value' => 'dblond','name' => $user->lang('DUNKELBLOND')),
			  array('id' => '16','groups' => 'augenfarbe','value' => 'grun','name' => $user->lang('GRUN')),
			  array('id' => '17','groups' => 'augenfarbe','value' => 'grau','name' => $user->lang('GRAU')),
			  array('id' => '18','groups' => 'augenfarbe','value' => 'braun','name' => $user->lang('BRAUN')),
			  array('id' => '19','groups' => 'augenfarbe','value' => 'gbraun','name' => $user->lang('GRUNBRAUN')),
			  array('id' => '20','groups' => 'augenfarbe','value' => 'blau','name' => $user->lang('BLAU')),
			  array('id' => '21','groups' => 'augenfarbe','value' => 'blgrun','name' => $user->lang('BLAUGRUN')),
			  array('id' => '22','groups' => 'augenfarbe','value' => 'bernstein','name' => $user->lang('BERNSTEIN')),
			  array('id' => '23','groups' => 'besondere_merkmale','value' => 'keine','name' => $user->lang('KEINE')),
			  array('id' => '24','groups' => 'besondere_merkmale','value' => 'narbe','name' => $user->lang('NARBE')),
			  array('id' => '25','groups' => 'besondere_merkmale','value' => 'schmuck','name' => $user->lang('SCHMUCK')),
			  array('id' => '26','groups' => 'besondere_merkmale','value' => 'deformierung','name' => $user->lang('DEFORMIERUNG')),
			  array('id' => '27','groups' => 'besondere_merkmale','value' => 'stark_ubergewichtig','name' => $user->lang('STARK_UBERGEWICHTIG')),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_figure', $figure_data);
	}

	public function insert_consim_item()
	{
		/** @var \phpbb\user $user */
		global $user;

		$inventory = array(
			array('id' => 1, 'name' => $user->lang('ITEM_1'), 'short_name' => $user->lang('ITEM_1_SHORT'), 'all_user' => 1),
			array('id' => 2, 'name' => $user->lang('ITEM_2'), 'short_name' => $user->lang('ITEM_2_SHORT'), 'all_user' => 1),
			array('id' => 3, 'name' => $user->lang('ITEM_3'), 'short_name' => $user->lang('ITEM_3_SHORT'), 'all_user' => 1),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_items', $inventory);
	}

	public function insert_consim_skill()
	{
		/** @var \phpbb\user $user */
		global $user;

		$skill = array(
			array('id' => 1, 'name' => $user->lang('SKILL_1'), 'cat' => $user->lang('SKILL_CAT_1'), 'country_id' => 1),
			array('id' => 2, 'name' => $user->lang('SKILL_2'), 'cat' => $user->lang('SKILL_CAT_1'), 'country_id' => 2),
			array('id' => 3, 'name' => $user->lang('SKILL_3'), 'cat' => $user->lang('SKILL_CAT_1'), 'country_id' => 3),
			array('id' => 4, 'name' => $user->lang('SKILL_4'), 'cat' => $user->lang('SKILL_CAT_2'), 'country_id' => 0),
			array('id' => 5, 'name' => $user->lang('SKILL_5'), 'cat' => $user->lang('SKILL_CAT_2'), 'country_id' => 0),
			array('id' => 6, 'name' => $user->lang('SKILL_6'), 'cat' => $user->lang('SKILL_CAT_2'), 'country_id' => 0),
			array('id' => 7, 'name' => $user->lang('SKILL_7'), 'cat' => $user->lang('SKILL_CAT_2'), 'country_id' => 0),
			array('id' => 8, 'name' => $user->lang('SKILL_8'), 'cat' => $user->lang('SKILL_CAT_2'), 'country_id' => 0),
			array('id' => 9, 'name' => $user->lang('SKILL_9'), 'cat' => $user->lang('SKILL_CAT_3'), 'country_id' => 0),
			array('id' => 10, 'name' => $user->lang('SKILL_10'), 'cat' => $user->lang('SKILL_CAT_3'), 'country_id' => 0),
			array('id' => 11, 'name' => $user->lang('SKILL_11'), 'cat' => $user->lang('SKILL_CAT_3'), 'country_id' => 0),
			array('id' => 12, 'name' => $user->lang('SKILL_12'), 'cat' => $user->lang('SKILL_CAT_3'), 'country_id' => 0),
			array('id' => 13, 'name' => $user->lang('SKILL_13'), 'cat' => $user->lang('SKILL_CAT_3'), 'country_id' => 0),
			array('id' => 14, 'name' => $user->lang('SKILL_14'), 'cat' => $user->lang('SKILL_CAT_4'), 'country_id' => 0),
			array('id' => 15, 'name' => $user->lang('SKILL_15'), 'cat' => $user->lang('SKILL_CAT_4'), 'country_id' => 0),
			array('id' => 16, 'name' => $user->lang('SKILL_16'), 'cat' => $user->lang('SKILL_CAT_4'), 'country_id' => 0),
			array('id' => 17, 'name' => $user->lang('SKILL_17'), 'cat' => $user->lang('SKILL_CAT_4'), 'country_id' => 0),
			array('id' => 18, 'name' => $user->lang('SKILL_18'), 'cat' => $user->lang('SKILL_CAT_4'), 'country_id' => 0),
			array('id' => 19, 'name' => $user->lang('SKILL_19'), 'cat' => $user->lang('SKILL_CAT_5'), 'country_id' => 0),
			array('id' => 20, 'name' => $user->lang('SKILL_20'), 'cat' => $user->lang('SKILL_CAT_5'), 'country_id' => 0),
			array('id' => 21, 'name' => $user->lang('SKILL_21'), 'cat' => $user->lang('SKILL_CAT_5'), 'country_id' => 0),
			array('id' => 22, 'name' => $user->lang('SKILL_22'), 'cat' => $user->lang('SKILL_CAT_5'), 'country_id' => 0),
			array('id' => 23, 'name' => $user->lang('SKILL_23'), 'cat' => $user->lang('SKILL_CAT_5'), 'country_id' => 0),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_skills', $skill);
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
				$this->table_prefix . 'consim_user',
				$this->table_prefix . 'consim_figure',
				$this->table_prefix . 'consim_actions',
				$this->table_prefix . 'consim_skills',
				$this->table_prefix . 'consim_user_skills',
				$this->table_prefix . 'consim_items',
				$this->table_prefix . 'consim_inventory_items',
			),
			'drop_columns'	=> array(
				$this->table_prefix . 'users' => array('consim_register'),
			),
			array('module.remove', array(
				'acp', 'ACP_CONSIM', array(
					'module_basename'	=> '\consim\core\acp\core_module',
					'modes'				=> array('news'),
				),
			)),
			array('module.remove', array('acp', 0, 'ACP_CONSIM')),
		);
	}
}
