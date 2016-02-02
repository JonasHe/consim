<?php

/**
*
* @package ConSim for phpBB3.1
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace consim\core\migrations;

class install_0_0_1 extends \phpbb\db\migration\migration
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

	public function update_data()
	{
		return array(
			// Set the current version
			array('config.add', array('consim_version', $this->consim_version)),
		);
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
				$this->table_prefix . 'consim_user'	=> array(
					'COLUMNS'		=> array(
						'user_id'				=> array('UINT:8', 0),
						'vorname'				=> array('VCHAR:255', ''),
						'nachname'				=> array('VCHAR:255', ''),
						'geschlecht'			=> array('VCHAR:1', ''),
						'geburtsland'			=> array('VCHAR:3', ''),
						'religion'				=> array('VCHAR:255', ''),
						'haarfarbe'				=> array('VCHAR:255', ''),
						'augenfarbe'			=> array('VCHAR:255', ''),
						'besondere_merkmale'	=> array('VCHAR:255', ''),
						'sprache_tadsowisch'	=> array('UINT:3', 1),
						'sprache_bakirisch'	=> array('UINT:3', 1),
						'sprache_suranisch'	=> array('UINT:3', 1),
						'rhetorik'				=> array('UINT:3', 1),
						'wirtschaft'			=> array('UINT:3', 1),
						'technik'				=> array('UINT:3', 1),
						'nahkampf'				=> array('UINT:3', 1),
						'schusswaffen'			=> array('UINT:3', 1),
						'militarkunde'			=> array('UINT:3', 1),
						'spionage'				=> array('UINT:3', 1),
						'medizin'				=> array('UINT:3', 1),
						'uberlebenskunde'		=> array('UINT:3', 1),
					),
					'PRIMARY_KEY'	=> array('user_id'),
				),
			),
			'add_columns'		=> array(
				$this->table_prefix . 'users'		=> array(
						'consim_register'		=> array('BOOL', 0),
				),
			),
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
			),
			'drop_columns'	=> array(
				$this->table_prefix . 'users' => array('consim_register'),
			),
		);
	}
}
