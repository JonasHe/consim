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
class Work extends abstractEntity
{
	/**
	 * All of fields of this objects
	 *
	 **/
	protected static $fields = array(
		'id'					=> 'integer',
		'name'					=> 'string',
		'duration'				=> 'integer',
		'building_type_id'		=> 'integer',
		'condition_1_id' 		=> 'integer',
		'condition_1_trials'	=> 'integer',
		'condition_1_value'		=> 'integer',
		'condition_1_name'		=> 'string',
		'condition_2_id' 		=> 'integer',
		'condition_2_trials'	=> 'integer',
		'condition_2_value'		=> 'integer',
		'condition_2_name'		=> 'string',
		'condition_3_id' 		=> 'integer',
		'condition_3_trials'	=> 'integer',
		'condition_3_value'		=> 'integer',
		'condition_3_name'		=> 'string',
		'experience_points'		=> 'unserializeExPoints',
	);

	/**
	 * Some fields must be unsigned (>= 0)
	 **/
	protected static $validate_unsigned = array(
		'id',
		'duration',
		'building_type_id',
		'condition_1_id',
		'condition_1_trials',
		'condition_1_value',
		'condition_2_id',
		'condition_2_trials',
		'condition_2_value',
		'condition_3_id',
		'condition_3_trials',
		'condition_3_value',
	);

	/**
	 * The database table the consim user data are stored in
	 * @var string
	 */
	protected $consim_work_table;
	protected $consim_skill_table;

	/**
	 * Class Variable to save all outputs
	 */
	protected $sorted_outputs = null;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface	$db					Database object
	 * @param string							$consim_work_table	Name of the table used to store data
	 * @param string							$consim_skill_table	Name of the table used to store data
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db,
		$consim_work_table,
		$consim_skill_table)
	{
		$this->db = $db;
		$this->consim_work_table = $consim_work_table;
		$this->consim_skill_table = $consim_skill_table;
	}

	/**
	 * Load the data from the database for this object
	 *
	 * @param int $id Work id
	 * @return Work $this object for chaining calls; load()->set()->save()
	 * @access public
	 * @throws \consim\core\exception\out_of_bounds
	 */
	public function load($id)
	{
		$sql = 'SELECT w.id, w.name, w.description, w.duration, w.building_type_id, 
				w.condition_1_id, w.condition_1_trials, w.condition_1_value, COALESCE(s1.name,"") AS condition_1_name,
				w.condition_2_id, w.condition_2_trials, w.condition_2_value, COALESCE(s2.name,"") AS condition_2_name,
				w.condition_3_id, w.condition_3_trials, w.condition_3_value, COALESCE(s3.name,"") AS condition_3_name,
				w.experience_points
			FROM ' . $this->consim_work_table . ' w
			LEFT JOIN '. $this->consim_skill_table .' s1 ON s1.id = w.condition_1_id
			LEFT JOIN '. $this->consim_skill_table .' s2 ON s2.id = w.condition_2_id
			LEFT JOIN '. $this->consim_skill_table .' s3 ON s3.id = w.condition_3_id
			WHERE w.id = '.  $id;
		$result = $this->db->sql_query($sql);
		$this->data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($this->data === false)
		{
			throw new \consim\core\exception\out_of_bounds('id');
		}

		//unserialized the experience points
		$this->data['experience_points'] = unserialize($this->data['experience_points']);

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
	 * Get Duration
	 *
	 * @return int Duration
	 * @access public
	 */
	public function getDuration()
	{
		return $this->getString($this->data['duration']);
	}

	/**
	 * Get Building Type Id
	 *
	 * @return int Building Type Id
	 * @access public
	 */
	public function getBuildingTypeId()
	{
		return $this->getInteger($this->data['building_type_id']);
	}

	/**
	 * Get Condition 1 Id
	 *
	 * @return int Condition Id
	 * @access public
	 */
	public function getCondition1Id()
	{
		return $this->getInteger($this->data['condition_1_id']);
	}

	/**
	 * Get Condition 1 Name
	 *
	 * @return string Condition Name
	 * @access public
	 */
	public function getCondition1Name()
	{
		return $this->getString($this->data['condition_1_name']);
	}

	/**
	 * Get Condition 1 Value
	 *
	 * @return int Condition Value
	 * @access public
	 */
	public function getCondition1Trials()
	{
		return $this->getInteger($this->data['condition_1_trials']);
	}

	/**
	 * Get Condition 1 Value
	 *
	 * @return int Condition Value
	 * @access public
	 */
	public function getCondition1Value()
	{
		return $this->getInteger($this->data['condition_1_value']);
	}

	/**
	 * Get Condition 2 Id
	 *
	 * @return int Condition Id
	 * @access public
	 */
	public function getCondition2Id()
	{
		return $this->getInteger($this->data['condition_2_id']);
	}

	/**
	 * Get Condition 2 Name
	 *
	 * @return string Condition Name
	 * @access public
	 */
	public function getCondition2Name()
	{
		return $this->getString($this->data['condition_2_name']);
	}

	/**
	 * Get Condition 2 Value
	 *
	 * @return int Condition Value
	 * @access public
	 */
	public function getCondition2Trials()
	{
		return $this->getInteger($this->data['condition_2_trials']);
	}

	/**
	 * Get Condition 2 Value
	 *
	 * @return int Condition Value
	 * @access public
	 */
	public function getCondition2Value()
	{
		return $this->getInteger($this->data['condition_2_value']);
	}

	/**
	 * Get Condition 3 Id
	 *
	 * @return int Condition Id
	 * @access public
	 */
	public function getCondition3Id()
	{
		return $this->getInteger($this->data['condition_3_id']);
	}

	/**
	 * Get Condition 3 Name
	 *
	 * @return string Condition Name
	 * @access public
	 */
	public function getCondition3Name()
	{
		return $this->getString($this->data['condition_3_name']);
	}

	/**
	 * Get Condition 3 Value
	 *
	 * @return int Condition Value
	 * @access public
	 */
	public function getCondition3Trials()
	{
		return $this->getInteger($this->data['condition_3_trials']);
	}

	/**
	 * Get Condition 3 Value
	 *
	 * @return int Condition Value
	 * @access public
	 */
	public function getCondition3Value()
	{
		return $this->getInteger($this->data['condition_3_value']);
	}

	/**
	 * Get experience points as Array
	 * start with 0!
	 *
	 * @return int[]
	 * @access public
	 */
	public function getExperiencePoints()
	{
		return $this->data['experience_points'];
	}

	/**
	 * unserialized experience points
	 *
	 * @param $string
	 * @return Work
	 */
	protected function unserializeExPoints($string)
	{
		$this->data['experience_points'] = unserialize($string);
		return $this;
	}
}
