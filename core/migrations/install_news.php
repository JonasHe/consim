<?php

/**
*
* @package ConSim for phpBB3.1
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace consim\core\migrations;

class install_news extends \phpbb\db\migration\migration
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
				$this->table_prefix . 'consim_news'	=> array(
					'COLUMNS'      => array(
						'news_id'               => array('UINT:8', NULL, 'auto_increment'),
						'channel_id'            => array('UINT:8', 0),
						'topic_id'            	=> array('UINT:8', 0),
						'content'            	=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> array('news_id'),
					'KEYS'			=> array(
						'channel_id'			=> array('INDEX', 'channel_id'),
					),
				),
				$this->table_prefix . 'consim_news_channel'	=> array(
					'COLUMNS'      => array(
						'channel_id'            => array('UINT:8', NULL, 'auto_increment'),
						'group_id'            	=> array('UINT:8', 0),
						'channel_name'          => array('VCHAR:255', ''),
						'vRefresh'            	=> array('UINT:1', 0),
						'background_color'      => array('VCHAR:255', ''),
						'color'         		=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> array('channel_id'),
				),
				$this->table_prefix . 'consim_news_topics'	=> array(
					'COLUMNS'      => array(
						'topic_id'              => array('UINT:8', NULL, 'auto_increment'),
						'topic_name'            => array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> array('topic_id'),
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
		return array();
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
				$this->table_prefix . 'consim_news',
				$this->table_prefix . 'consim_news_channel',
				$this->table_prefix . 'consim_news_topics',
			),
		);
	}
}
