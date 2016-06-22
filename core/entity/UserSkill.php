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
class UserSkill extends abstractEntity
{
	/**
	 * All of fields of this objects
	 **/
	protected static $fields = array(
		'id'				=> 'integer',
		'user_id'			=> 'integer',
		'skill_id'			=> 'integer',
		'skill_name'		=> 'string',
		'value'				=> 'integer'
	);

	/**
	 * Some fields must be unsigned (>= 0)
	 **/
	protected static $validate_unsigned = array(
		'id',
		'user_id',
		'skill_id',
		'value'
	);
	/**
	 * The database table the consim user data are stored in
	 * @var string
	 */
	protected $consim_skill_table;
	protected $consim_user_skill_table;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface		$db							Database object
	 * @param string								$consim_skill_table			Name of the table used to store data
	 * @param string								$consim_user_skill_table	Name of the table used to store data
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db, $consim_skill_table, $consim_user_skill_table)
	{
		$this->db = $db;
		$this->consim_skill_table = $consim_skill_table;
		$this->consim_user_skill_table = $consim_user_skill_table;
	}

	/**
	 * Load the data from the database for this object
	 *
	 * @param int $user_id
	 * @param int $skill_id
	 * @return UserSkill $this object for chaining calls; load()->set()->save()
	 * @access public
	 * @throws \consim\core\exception\out_of_bounds
	 */
	public function load($user_id, $skill_id)
	{
		$sql = 'SELECT us.id, us.user_id, us.skill_id, s.name AS skill_name, us.value
			FROM ' . $this->consim_user_skill_table .' us
			LEFT JOIN '. $this->consim_skill_table .' s ON s.id = us.skill_id
			WHERE us.user_id = '. $user_id .' AND us.skill_id = '. $skill_id;
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
	 * @param int $skill_id
	 * @param int $value
	 * @param bool $reload To use this entity later
	 * @return UserSkill|null $this object for chaining calls; load()->set()->save()
	 * @access public
	 * @throws \consim\core\exception\out_of_bounds
	 */
	public function insert($user_id, $skill_id, $value, $reload = false)
	{
		if (!empty($this->data['id']))
		{
			// The page already exists
			throw new \consim\core\exception\out_of_bounds('id');
		}

		$data = array(
			'user_id'	=> $user_id,
			'skill_id'	=> $skill_id,
			'value'		=> $value,
		);

		// Insert the page data to the database
		$sql = 'INSERT INTO ' . $this->consim_user_skill_table . ' ' . $this->db->sql_build_array('INSERT', $data);
		$this->db->sql_query($sql);

		if($reload)
		{
			//reload this entity
			return $this->load($user_id, $skill_id);
		}
		return null;
	}

	/**
	 * Save the current settings to the database
	 *
	 * This must be called before closing or any changes will not be saved!
	 * If adding a rule (saving for the first time), you must call insert() or an exeception will be thrown
	 *
	 * @return UserSkill $this object for chaining calls; load()->set()->save()
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

		$sql = 'UPDATE '. $this->consim_user_skill_table .'
			SET value = ' . $this->data['value'] . '
			WHERE id = '. $this->getId();
		$this->db->sql_query($sql);

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
	 * Get User ID
	 *
	 * @return int ID
	 * @access public
	 */
	public function getUserId()
	{
		return $this->getInteger($this->data['user_id']);
	}

	/**
	 * Get Skill ID
	 *
	 * @return int ID
	 * @access public
	 */
	public function getSkillId()
	{
		return $this->getInteger($this->data['skill_id']);
	}

	/**
	 * Get Name
	 *
	 * @return string Name
	 * @access public
	 */
	public function getSkillName()
	{
		return $this->getString($this->data['skill_name']);
	}

	/**
	 * Get Value
	 *
	 * @return int ID
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
	 * @return UserSkill
	 * @access public
	 */
	public function setValue($value)
	{
		return $this->setInteger('value', $this->data['value']);
	}
}
