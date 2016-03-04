<?php

/**
*
* @package ConSim for phpBB3.1
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
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
			'add_tables'		=> array(
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
						'sprache_tadsowisch'	=> array('UINT:3', 1),
						'sprache_bakirisch'		=> array('UINT:3', 1),
						'sprache_suranisch'		=> array('UINT:3', 1),
						'rhetorik'				=> array('UINT:3', 1),
						'administration'		=> array('UINT:3', 1),
						'wirtschaft'			=> array('UINT:3', 1),
						'technik'				=> array('UINT:3', 1),
						'nahkampf'				=> array('UINT:3', 1),
						'schusswaffen'			=> array('UINT:3', 1),
						'sprengmittel'			=> array('UINT:3', 1),
						'militarkunde'          => array('UINT:3', 1),
						'spionage'				=> array('UINT:3', 1),
						'schmuggel'				=> array('UINT:3', 1),
						'medizin'				=> array('UINT:3', 1),
						'uberlebenskunde'		=> array('UINT:3', 1),
                        'location_id'           => array('UINT:8', 4),
                        'active'                => array('BOOL', 0),
					),
					'PRIMARY_KEY'	=> array('user_id'),
				),
				$this->table_prefix . 'consim_figure' => array(
					'COLUMNS'		=> array(
						'id'					=> array('UINT:8'),
						'desc'			        => array('VCHAR:255', ''),
						'value'					=> array('VCHAR:255', ''),
						'translate'				=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> array('id'),
				),
                $this->table_prefix . 'consim_actions' => array(
					'COLUMNS'		=> array(
						'id'					=> array('UINT:8', 'auto_increment'),
						'user_id'         		=> array('UINT:8', 0),
						'starttime'				=> array('UINT:8', 0),
                        'endtime'               => array('TIMESTAMP', 0),
                        'travel_id'             => array('UINT:8', 0),
                        'status'                => array('BOOL', 0),
					),
					'PRIMARY_KEY'	=> array('id'),
                    'KEYS'			=> array(
						'starttime'       => array('INDEX', 'starttime'),
                        'endtime'         => array('INDEX', 'endtime'),
                        'travel_id'       => array('INDEX', 'travel_id'),
                        'status'          => array('INDEX', 'status'),
					),
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
            array('custom', array(array($this, 'insert_consim_figure'))),
        );
    }

	public function insert_consim_figure()
	{
		$figure_data = array(
			  array('id' => '1','desc' => 'geschlecht','value' => 'm','translate' => 'MANNLICH'),
			  array('id' => '2','desc' => 'geschlecht','value' => 'w','translate' => 'WEIBLICH'),
			  array('id' => '3','desc' => 'geburtsland','value' => 'frt','translate' => 'FRT'),
			  array('id' => '4','desc' => 'geburtsland','value' => 'bak','translate' => 'BAK'),
			  array('id' => '5','desc' => 'geburtsland','value' => 'sur','translate' => 'SUR'),
			  array('id' => '6','desc' => 'religion','value' => 'orthodox','translate' => 'ORTHODOX'),
			  array('id' => '7','desc' => 'religion','value' => 'katholisch','translate' => 'KATHOLISCH'),
			  array('id' => '8','desc' => 'religion','value' => 'muslimisch','translate' => 'MUSLIMISCH'),
			  array('id' => '9','desc' => 'religion','value' => 'atheistisch','translate' => 'ATHEISTISCH'),
			  array('id' => '10','desc' => 'haarfarbe','value' => 'schwarz','translate' => 'SCHWARZ'),
			  array('id' => '11','desc' => 'haarfarbe','value' => 'rot','translate' => 'ROT'),
			  array('id' => '12','desc' => 'haarfarbe','value' => 'hbraun','translate' => 'HELLBRAUN'),
			  array('id' => '13','desc' => 'haarfarbe','value' => 'dbraun','translate' => 'DUNKELBRAUN'),
			  array('id' => '14','desc' => 'haarfarbe','value' => 'blond','translate' => 'BLOND'),
			  array('id' => '15','desc' => 'haarfarbe','value' => 'dblond','translate' => 'DUNKELBLOND'),
			  array('id' => '16','desc' => 'augenfarbe','value' => 'grun','translate' => 'GRUN'),
			  array('id' => '17','desc' => 'augenfarbe','value' => 'grau','translate' => 'GRAU'),
			  array('id' => '18','desc' => 'augenfarbe','value' => 'braun','translate' => 'BRAUN'),
			  array('id' => '19','desc' => 'augenfarbe','value' => 'gbraun','translate' => 'GRUNBRAUN'),
			  array('id' => '20','desc' => 'augenfarbe','value' => 'blau','translate' => 'BLAU'),
			  array('id' => '21','desc' => 'augenfarbe','value' => 'blgrun','translate' => 'BLAUGRUN'),
			  array('id' => '22','desc' => 'augenfarbe','value' => 'bernstein','translate' => 'BERNSTEIN'),
			  array('id' => '23','desc' => 'besondere_merkmale','value' => 'keine','translate' => 'KEINE'),
			  array('id' => '24','desc' => 'besondere_merkmale','value' => 'narbe','translate' => 'NARBE'),
			  array('id' => '25','desc' => 'besondere_merkmale','value' => 'schmuck','translate' => 'SCHMUCK'),
			  array('id' => '26','desc' => 'besondere_merkmale','value' => 'deformierung','translate' => 'DEFORMIERUNG'),
			  array('id' => '27','desc' => 'besondere_merkmale','value' => 'stark_ubergewichtig','translate' => 'STARK_UBERGEWICHTIG'),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_figure', $figure_data);
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
			),
			'drop_columns'	=> array(
				$this->table_prefix . 'users' => array('consim_register'),
			),
		);
	}
}
