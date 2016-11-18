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

class m2_basic_data extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\consim\core\migrations\v10x\m1_inital_schema');
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
			// Set config
			//array('config.add', array('consim_weather_api_key', '')),

			array('custom', array(array($this, 'insert_consim_figure'))),
			array('custom', array(array($this, 'insert_consim_item'))),
			array('custom', array(array($this, 'insert_consim_skill'))),
			array('custom', array(array($this, 'insert_consim_weather'))),
			array('custom', array(array($this, 'insert_location_types'))),
			array('custom', array(array($this, 'insert_provinces'))),
			array('custom', array(array($this, 'insert_countries'))),
			array('custom', array(array($this, 'insert_asset_types'))),
			array('custom', array(array($this, 'insert_assets'))),

			// Add an acp tab
			array('module.add', array('acp', 0, 'ACP_CAT_CONSIM')),
			array('module.add', array('acp', 'ACP_CAT_CONSIM', 'ACP_CAT_CONSIMMODULES')),
			array('module.add', array(
				'acp', 'ACP_CAT_CONSIMMODULES', array(
					'module_basename'	=> '\consim\core\acp\core_module',
					'modes'				=> array('news','anniversary','map'),
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

	public function insert_consim_weather()
	{
		$weather_data = array(
			array('prvnce_id' => '1','owm_id' => '523523','last_updated' => time()-(24 * 60 * 60)),
			array('prvnce_id' => '2','owm_id' => '839788','last_updated' => time()-(24 * 60 * 60)),
			array('prvnce_id' => '3','owm_id' => '561560','last_updated' => time()-(24 * 60 * 60)),
			array('prvnce_id' => '4','owm_id' => '478711','last_updated' => time()-(24 * 60 * 60)),
			array('prvnce_id' => '5','owm_id' => '587081','last_updated' => time()-(24 * 60 * 60)),
			array('prvnce_id' => '6','owm_id' => '148340','last_updated' => time()-(24 * 60 * 60)),
			array('prvnce_id' => '7','owm_id' => '585514','last_updated' => time()-(24 * 60 * 60)),
			array('prvnce_id' => '8','owm_id' => '611373','last_updated' => time()-(24 * 60 * 60)),
			array('prvnce_id' => '9','owm_id' => '614455','last_updated' => time()-(24 * 60 * 60)),
			array('prvnce_id' => '10','owm_id' => '613226','last_updated' => time()-(24 * 60 * 60)),
			array('prvnce_id' => '11','owm_id' => '611847','last_updated' => time()-(24 * 60 * 60)),
			array('prvnce_id' => '12','owm_id' => '824385','last_updated' => time()-(24 * 60 * 60)),
			array('prvnce_id' => '13','owm_id' => '751864','last_updated' => time()-(24 * 60 * 60)),
			array('prvnce_id' => '14','owm_id' => '615419','last_updated' => time()-(24 * 60 * 60)),
			array('prvnce_id' => '15','owm_id' => '585226','last_updated' => time()-(24 * 60 * 60)),
			array('prvnce_id' => '16','owm_id' => '616769','last_updated' => time()-(24 * 60 * 60)),
			array('prvnce_id' => '17','owm_id' => '174769','last_updated' => time()-(24 * 60 * 60)),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_weather', $weather_data);
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

	public function insert_asset_types()
	{
		/** @var \phpbb\user $user */
		global $user;

		$countries = array(
			array('id' => 1, 'name' => $user->lang('CURRENCIES')),
			array('id' => 2, 'name' => $user->lang('BONDS')),
			array('id' => 3, 'name' => $user->lang('SHARES')),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_asset_types', $countries);
	}

	public function insert_assets()
	{
		/** @var \phpbb\user $user */
		global $user;

		$countries = array(
			array('id' => 1, 'type_id' => 1, 'name' => $user->lang('CURRENCY_1'), 'short_name' => $user->lang('CURRENCY_1_SHORT')),
			array('id' => 2, 'type_id' => 1, 'name' => $user->lang('CURRENCY_2'), 'short_name' => $user->lang('CURRENCY_2_SHORT')),
			array('id' => 3, 'type_id' => 1, 'name' => $user->lang('CURRENCY_3'), 'short_name' => $user->lang('CURRENCY_3_SHORT')),
			array('id' => 4, 'type_id' => 2, 'name' => $user->lang('BOND_1'), 'short_name' => $user->lang('BOND_1_SHORT')),
			array('id' => 5, 'type_id' => 2, 'name' => $user->lang('BOND_2'), 'short_name' => $user->lang('BOND_2_SHORT')),
			array('id' => 6, 'type_id' => 2, 'name' => $user->lang('BOND_3'), 'short_name' => $user->lang('BOND_3_SHORT')),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_assets', $countries);
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
			array('module.remove', array(
				'acp', 'ACP_CAT_CONSIMMODULES', array(
					'module_basename'	=> '\consim\core\acp\core_module',
					'modes'				=> array('news','anniversary','map'),
				),
			)),
			array('module.remove', array('acp', 'ACP_CAT_CONSIM', 'ACP_CAT_CONSIMMODULES')),
			array('module.remove', array('acp', 0, 'ACP_CAT_CONSIM')),
		);
	}

}
