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
		'condition_id' 			=> 'integer',
		'condition_name'		=> 'string',
		'condition_value'		=> 'integer',
		'output_id'				=> 'integer',
		'output_name'			=> 'string',
		'output_value'			=> 'integer',
	);

	/**
	 * Some fields must be unsigned (>= 0)
	 **/
	protected static $validate_unsigned = array(
		'id',
		'duration',
		'building_type_id',
		'condition_id',
		'condition_value',
		'output_id',
		'output_value',
	);
	/**
	 * The database table the consim user data are stored in
	 * @var string
	 */
	protected $consim_work_table;
	protected $consim_skill_table;
	protected $consim_item_table;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface	$db					Database object
	 * @param string							$consim_work_table	Name of the table used to store data
	 * @param string							$consim_skill_table	Name of the table used to store data
	 * @param string							$consim_item_table	Name of the table used to store data
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db,
								$consim_work_table,
								$consim_skill_table,
								$consim_item_table)
	{
		$this->db = $db;
		$this->consim_work_table = $consim_work_table;
		$this->consim_skill_table = $consim_skill_table;
		$this->consim_item_table = $consim_item_table;
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
				w.condition_id, w.condition_value, w.output_id, w.output_value,
				COALESCE(s.name,"") AS condition_name, COALESCE(i.name, "") AS output_name
			FROM ' . $this->consim_work_table . ' w
			LEFT JOIN '. $this->consim_skill_table .' s ON s.id = w.condition_id
			LEFT JOIN '. $this->consim_item_table .' i ON i.id = w.output_id
			WHERE w.id = '.  $id;
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
	 * Get Condition Id
	 *
	 * @return int Condition Id
	 * @access public
	 */
	public function getConditionId()
	{
		return $this->getInteger($this->data['condition_id']);
	}

	/**
	 * Get Condition Name
	 *
	 * @return string Condition Name
	 * @access public
	 */
	public function getConditionName()
	{
		return $this->getString($this->data['condition_name']);
	}

	/**
	 * Get Condition Value
	 *
	 * @return int Condition Value
	 * @access public
	 */
	public function getConditionValue()
	{
		return $this->getInteger($this->data['condition_value']);
	}

	/**
	 * Get Output Id
	 *
	 * @return int Output Id
	 * @access public
	 */
	public function getOutputId()
	{
		return $this->getInteger($this->data['output_id']);
	}

	/**
	 * Get Output Name
	 *
	 * @return string Output Name
	 * @access public
	 */
	public function getOutputName()
	{
		return $this->getString($this->data['output_name']);
	}

	/**
	 * Get Output Value
	 *
	 * @return int Output Value
	 * @access public
	 */
	public function getOutputValue()
	{
		return $this->getInteger($this->data['output_value']);
	}
}
