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
	 * @param int $id Id
	 *
	 * @return Item $this object for chaining calls; load()->set()->save()
	 * @access public
	 * @throws \consim\core\exception\out_of_bounds
	 */
	public function load($id)
	{
		$sql = 'SELECT id, name
			FROM ' . $this->consim_skill_table . '
			WHERE id = '. (int) $id;
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
}
