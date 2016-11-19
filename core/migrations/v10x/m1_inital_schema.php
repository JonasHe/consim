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

class m1_inital_schema extends \phpbb\db\migration\migration
{
	private $consim_version = '0.0.1';

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
						'result'				=> array('TEXT_UNI', ''),
						'successful_trials'		=> array('UINT:8', 0), //TODO: Sinnvoll? Nützlich?
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
				$this->table_prefix . 'consim_anniversary' => array(
					'COLUMNS'		=> array(
						'anniversary_id'		=> array('UINT:8', NULL, 'auto_increment'),
						'day'					=> array('UINT:2',0),
						'month'					=> array('UINT:2',0),
						'year'					=> array('UINT:2',0),
						'event'					=> array('VCHAR:255',''),
						'link'					=> array('VCHAR:255',''),
					),
					'PRIMARY_KEY'	=> array('anniversary_id'),
				),
				$this->table_prefix . 'consim_weather' => array(
					'COLUMNS'		=> array(
						'prvnce_id'				=> array('UINT:8', 0),
						'owm_id'				=> array('UINT:8', 0),
						'last_updated'			=> array('TIMESTAMP', 0),
						'weather'				=> array('VCHAR:255', ''),
						'weather_image'			=> array('VCHAR:255', ''),
						'rain'					=> array('VCHAR:255', ''),
						'temperature'			=> array('VCHAR:255', ''),
						'wind_speed'			=> array('UINT:2', 0),
						'wind_direction'		=> array('UINT:3', 0),
					),
					'KEYS'			=> array(
						'prvnce_id'		=> array('INDEX', 'prvnce_id'),
						'owm_id'		=> array('INDEX', 'owm_id'),
					),
				),
				$this->table_prefix . 'consim_news'	=> array(
					'COLUMNS'	=> array(
						'news_id'			=> array('UINT:8', NULL, 'auto_increment'),
						'channel_id'		=> array('UINT:8', 0),
						'topic_id'			=> array('UINT:8', 0),
						'content'			=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> array('news_id'),
					'KEYS'			=> array(
						'channel_id'			=> array('INDEX', 'channel_id'),
					),
				),
				$this->table_prefix . 'consim_news_channel'	=> array(
					'COLUMNS'	=> array(
						'channel_id'			=> array('UINT:8', NULL, 'auto_increment'),
						'group_id'				=> array('UINT:8', 0),
						'channel_name'			=> array('VCHAR:255', ''),
						'vRefresh'				=> array('UINT:1', 0),
						'background_color'		=> array('VCHAR:255', ''),
						'color'					=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> array('channel_id'),
				),
				$this->table_prefix . 'consim_news_topics'	=> array(
					'COLUMNS'	=> array(
						'topic_id'				=> array('UINT:8', NULL, 'auto_increment'),
						'topic_name'			=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> array('topic_id'),
				),
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
						'id'					=> array('UINT:8', NULL, 'auto_increment'),
						'name'					=> array('VCHAR:255', ''),
						'x'						=> array('UINT:8', 0),
						'y'						=> array('UINT:8', 0),
						'type'					=> array('UINT:8', 0),
						'map_name'				=> array('VCHAR:255','')
					),
					'PRIMARY_KEY'	=> array('id'),
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
				$this->table_prefix. 'consim_asset_types'	=> array(
					'COLUMNS'	=> array(
						'id'					=> array('UINT:8', 0),
						'name'					=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> array('id'),
				),
				$this->table_prefix. 'consim_assets'	=> array(
					'COLUMNS'	=> array(
						'id'					=> array('UINT:8', 0),
						'type_id'				=> array('UINT:8', 0),
						'name'					=> array('VCHAR:255', ''),
						'short_name'			=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> array('id'),
				),
				$this->table_prefix. 'consim_users_assets'	=> array(
					'COLUMNS'	=> array(
						'id'					=> array('UINT:8', NULL, 'auto_increment'),
						'user_id'				=> array('UINT:8', 0),
						'asset_id'				=> array('UINT:8', 0),
						'value'					=> array('UINT:8', 0),
					),
					'PRIMARY_KEY'	=> array('id'),
					'KEYS'			=> array(
						'u_id'					=> array('INDEX', 'user_id'),
						'a_id'					=> array('INDEX', 'asset_id'),
					),
				),
			),
			/**
			 * Add Columns
			 */
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
		return array(
			// Set the current version
			array('config.add', array('consim_version', $this->consim_version)),
		);
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
				$this->table_prefix . 'consim_anniversary',
				$this->table_prefix . 'consim_weather',
				$this->table_prefix . 'consim_news',
				$this->table_prefix . 'consim_news_channel',
				$this->table_prefix . 'consim_news_topics',
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
				$this->table_prefix . 'consim_asset_types',
				$this->table_prefix . 'consim_assets',
				$this->table_prefix . 'consim_users_assets',
			),
			'drop_columns'	=> array(
				$this->table_prefix . 'users' => array('consim_register'),
			)
		);
	}

}
