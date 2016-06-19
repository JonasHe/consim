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
class InventoryItem extends abstractEntity
{
	/**
	 * All of fields of this objects
	 *
	 **/
	protected static $fields = array(
		'id'				=> 'integer',
		'user_id'			=> 'integer',
		'value'				=> 'integer',
		'item_id'			=> 'integer',
		'item_name'			=> 'string',
		'item_short_name'	=> 'string',
	);

	/**
	 * Some fields must be unsigned (>= 0)
	 **/
	protected static $validate_unsigned = array(
		'id',
		'user_id',
		'item_id',
		'value',
	);
	/**
	 * The database table the consim user data are stored in
	 * @var string
	 */
	protected $consim_inventory_item_table;
	protected $consim_item_table;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface		$db								Database object
	 * @param string								$consim_inventory_item_table	Name of the table used to store data
	 * @param string								$consim_item_table				Name of the table used to store data
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db,
								$consim_inventory_item_table,
								$consim_item_table)
	{
		$this->db = $db;
		$this->consim_inventory_item_table = $consim_inventory_item_table;
		$this->consim_item_table = $consim_item_table;
	}

	/**
	 * Load the data from the database for this object
	 *
	 * @param int $id Id
	 * @return InventoryItem $this object for chaining calls; load()->set()->save()
	 * @access public
	 * @throws \consim\core\exception\out_of_bounds
	 */
	public function load($id)
	{
		$sql = 'SELECT i.id, i.user_id, i.value,
					item.id AS item_id, item.name AS item_name, item.short_name AS item_short_name
				FROM ' . $this->consim_inventory_item_table . ' i
				LEFT JOIN '. $this->consim_item_table .' item ON item.id = i.item_id
				WHERE i.id = '. (int) $id;
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
	 * Insert the Data for the first time
	 *
	 * Will throw an exception if the data was already inserted (call save() instead)
	 *
	 * @param int $user_id
	 * @param int $item_id
	 * @param int $value
	 * @param bool $reload To use this entity later
	 * @return InventoryItem|null $this object for chaining calls; load()->set()->save()
	 * @access public
	 * @throws \consim\core\exception\out_of_bounds
	 */
	public function insert($user_id, $item_id, $value, $reload = false)
	{
		if (!empty($this->data['id']))
		{
			// The page already exists
			throw new \consim\core\exception\out_of_bounds('id');
		}

		$data = array(
			'user_id'	=> $user_id,
			'item_id'	=> $item_id,
			'value'		=> $value,
		);

		// Insert the page data to the database
		$sql = 'INSERT INTO ' . $this->consim_inventory_item_table . ' ' . $this->db->sql_build_array('INSERT', $data);
		$this->db->sql_query($sql);

		if($reload)
		{
			//reload this entity
			return $this->load((int) $this->db->sql_nextid());
		}
		return null;
	}

	/**
	 * Save the current settings to the database
	 *
	 * This must be called before closing or any changes will not be saved!
	 * If adding a rule (saving for the first time), you must call insert() or an exeception will be thrown
	 *
	 * @return InventoryItem $this object for chaining calls; load()->set()->save()
	 * @access public
	 * @throws \consim\core\exception\out_of_bounds
	 */
	public function save()
	{
		if (empty($this->data['id']))
		{
			// The rule does not exist
			throw new \consim\core\exception\out_of_bounds('rule_id');
		}

		$sql = 'UPDATE '. $this->consim_inventory_item_table .'
			SET value = ' . $this->data['value'] . '
			WHERE id = '. $this->getId();
		$this->db->sql_query($sql);

		return $this;
	}

	/**
	 * Get User ID
	 *
	 * @return int ID
	 * @access public
	 */
	public function getId()
	{
		return $this->getInteger($this->data['id']);
	}

	/**
	 * Get Value
	 *
	 * @return int Value
	 * @access public
	 */
	public function getValue()
	{
		return $this->getInteger($this->data['value']);
	}

	/**
	 * Set Value
	 *
	 * @param int $value
	 * @return InventoryItem
	 * @access public
	 */
	public function setValue($value)
	{
		return $this->setInteger('value', $value);
	}

	/**
	 * Get Item ID
	 *
	 * @return int Item Id
	 * @access public
	 */
	public function getItemId()
	{
		return $this->getInteger($this->data['item_id']);
	}

	/**
	 * Get item name
	 *
	 * @return string item name
	 * @access public
	 */
	public function getItemName()
	{
		return $this->getString($this->data['item_name']);
	}

	/**
	 * Get item short name
	 *
	 * @return string item short name
	 * @access public
	 */
	public function getItemShortName()
	{
		return $this->getString($this->data['item_short_name']);
	}
}
