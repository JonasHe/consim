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
class UserAsset extends \consim\core\entity\Asset
{
	/**
	 * All of fields of this objects
	 *
	 **/
	protected static $fields = array(
		'id'				=> 'integer',
		'user_id'			=> 'integer',
		'asset_id'			=> 'integer',
		'type_id'			=> 'integer',
		'type_name'			=> 'string',
		'name'				=> 'string',
		'short_name'		=> 'string',
		'value'				=> 'integer',
	);

	/**
	 * Some fields must be unsigned (>= 0)
	 **/
	protected static $validate_unsigned = array(
		'id',
		'user_id',
		'asset_id',
		'type_id',
		'value',
	);

	/**
	 * The database table where the data are stored in
	 * @var string
	 */
	protected $consim_user_asset_table;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface		$db							Database object
	 * @param string								$consim_user_asset_table	Name of the table used to store data
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db, $consim_user_asset_table)
	{
		$this->db = $db;
		$this->consim_user_asset_table = $consim_user_asset_table;
	}

	/**
	 * NOT USED!
	 *
	 * @param int $id
	 * @return null
	 */
	public function load($id)
	{
		return null;
	}

	/**
	 * Save the current settings to the database
	 *
	 * This must be called before closing or any changes will not be saved!
	 * If adding a rule (saving for the first time), you must call insert() or an exeception will be thrown
	 *
	 * @return UserAsset $this object for chaining calls; load()->set()->save()
	 * @access public
	 * @throws \consim\core\exception\out_of_bounds
	 */
	public function save()
	{
		if (empty($this->data['id']))
		{
			// The rule does not exist
			throw new \consim\core\exception\out_of_bounds('id');
		}

		$sql = 'UPDATE '. $this->consim_user_asset_table .'
			SET value = ' . $this->data['value'] . '
			WHERE id = '. $this->getId();
		$this->db->sql_query($sql);

		return $this;
	}

	/**
	 * Get UserId
	 *
	 * @return int UserId
	 * @access public
	 */
	public function getUserId()
	{
		return $this->getInteger($this->data['user_id']);
	}

	/**
	 * Get asset id
	 *
	 * @return int Asset id
	 * @access public
	 */
	public function getAssetId()
	{
		return $this->getInteger($this->data['asset_id']);
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
	 * @return UserAsset
	 * @access public
	 */
	public function setValue($value)
	{
		return $this->setInteger('value', $value);
	}
}