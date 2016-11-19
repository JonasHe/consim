<?php
/**
 * @package ConSim for phpBB3.1
 *
 * @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace consim\core\entity;

/**
 * Entity
 */
class Asset extends abstractEntity
{
	/**
	 * All of fields of this objects
	 *
	 **/
	protected static $fields = array(
		'id'				=> 'integer',
		'type_id'			=> 'integer',
		'type_name'			=> 'string',
		'name'				=> 'string',
		'short_name'		=> 'string',
	);

	/**
	 * Some fields must be unsigned (>= 0)
	 **/
	protected static $validate_unsigned = array(
		'id',
		'type_id',
	);

	/**
	 * The database table where the data are stored in
	 * @var string
	 */
	protected $consim_asset_table;
	protected $consim_asset_type_table;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface		$db							Database object
	 * @param string								$consim_asset_table			Name of the table used to store data
	 * @param string								$consim_asset_type_table	Name of the table used to store data
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db, $consim_asset_table, $consim_asset_type_table)
	{
		$this->db = $db;
		$this->consim_asset_table = $consim_asset_table;
		$this->consim_asset_type_table = $consim_asset_type_table;
	}

	/**
	 * Load the data from the database for this object
	 *
	 * @param int $id Id
	 *
	 * @return Asset $this object for chaining calls; load()->set()->save()
	 * @access public
	 * @throws \consim\core\exception\out_of_bounds
	 */
	public function load($id)
	{
		$sql = 'SELECT a.id, a.type_id, at.name as type_name, a.name, a.short_name
			FROM '. $this->consim_asset_table .' a
			LEFT JOIN '. $this->consim_asset_type_table .' at ON at.id = a.type_id 
			WHERE a.id = '. (int) $id;
		$result = $this->db->sql_query($sql);
		$this->data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($this->data === false)
		{
			throw new \consim\core\exception\out_of_bounds('id');
		}

		return $this;
	}

	/**
	 * Get ID
	 *
	 * @return int ID
	 * @access public
	 */
	public function getId()
	{
		return $this->getInteger($this->data['id']);
	}

	/**
	 * Get Type ID
	 *
	 * @return int ID
	 * @access public
	 */
	public function getTypeId()
	{
		return $this->getInteger($this->data['type_id']);
	}

	/**
	 * Get Type Name
	 *
	 * @return string Type Name
	 * @access public
	 */
	public function getTypeName()
	{
		return $this->getString($this->data['type_name']);
	}

	/**
	 * Get Name
	 *
	 * @return string Name
	 * @access public
	 */
	public function getName()
	{
		return $this->getString($this->data['name']);
	}

	/**
	 * Get Short Name
	 *
	 * @return string Short Name
	 * @access public
	 */
	public function getShortName()
	{
		return $this->getString($this->data['short_name']);
	}
}