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
		'condition_type' 		=> 'string',
		'condition_value'		=> 'integer',
		'output_type'			=> 'string',
		'output_value'			=> 'integer',
	);

	/**
	 * Some fields must be unsigned (>= 0)
	 **/
	protected static $validate_unsigned = array(
		'id',
		'duration',
		'building_type_id',
		'condition_value',
		'output_value',
	);
	/**
	 * The database table the consim user data are stored in
	 * @var string
	 */
	protected $consim_work_table;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface	$db					Database object
	 * @param string							$consim_work_table	Name of the table used to store data
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db, $consim_work_table)
	{
		$this->db = $db;
		$this->consim_work_table = $consim_work_table;
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
		$sql = 'SELECT id, name, duration, building_type_id, condition_type, condition_value, output_type, output_value
			FROM ' . $this->consim_work_table . '
			WHERE id = '.  $id;
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
	 * Get Condition Type
	 *
	 * @return string Condition Type
	 * @access public
	 */
	public function getConditionType()
	{
		return $this->getString($this->data['condition_type']);
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
	 * Get Output Type
	 *
	 * @return string Output Type
	 * @access public
	 */
	public function getOutputType()
	{
		return $this->getString($this->data['output_type']);
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
