<?php
/**
 * @package ConSim for phpBB3.1
 *
 * @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace consim\core\entity;

/**
 * Entity for a single ressource
 * To Insert a new travelaction
 */
class Working extends Work
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
	protected $consim_action_table;
	protected $consim_work_table;
	protected $consim_skill_table;
	protected $consim_item_table;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface	$db							Database object
	 * @param string								$consim_action_table	Name of the table used to store data
	 * @param string								$consim_work_table		Name of the table used to store data
	 * @param string								$consim_skill_table		Name of the table used to store data
	 * @param string								$consim_item_table		Name of the table used to store data
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db,
								$consim_action_table,
								$consim_work_table,
								$consim_skill_table,
								$consim_item_table)
	{
		$this->db = $db;
		$this->consim_action_table = $consim_action_table;
		$this->consim_work_table = $consim_work_table;
		$this->consim_skill_table = $consim_skill_table;
		$this->consim_item_table = $consim_item_table;
	}

	/**
	 * Load the data from the database for this object
	 *
	 * @param int $id Work id
	 * @return Working $this object for chaining calls; load()->set()->save()
	 * @access public
	 * @throws \consim\core\exception\out_of_bounds
	 */
	public function load($id)
	{
		$sql = 'SELECT w.id, w.name, w.description, w.duration, w.building_type_id, 
				w.condition_id, w.condition_value, w.output_id, w.output_value,
				COALESCE(s.name,"") AS condition_name, COALESCE(i.name, "") AS output_name,
				a.id AS action_id, a.user_id, a.starttime, a.endtime, a.location_id, a.status
			FROM '. $this->consim_action_table .' a
			LEFT JOIN '. $this->consim_work_table .' w ON w.id = a.work_id
			LEFT JOIN '. $this->consim_skill_table .' s ON s.id = w.condition_id
			LEFT JOIN '. $this->consim_item_table .' i ON i.id = w.output_id
			WHERE a.id = '.  $id;
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
	 * Get action id
	 *
	 * @return int action id
	 * @access public
	 */
	public function getActionId()
	{
		return $this->getInteger($this->data['action_id']);
	}

	/**
	 * Get User ID
	 *
	 * @return int User ID
	 * @access public
	 */
	public function getUserId()
	{
		return $this->getInteger($this->data['user_id']);
	}

	/**
	 * Get location id
	 *
	 * @return int location id
	 * @access public
	 */
	public function getLocationId()
	{
		return $this->getInteger($this->data['location_id']);
	}

	/**
	 * Get Start Time
	 *
	 * @return int Starttime
	 * @access public
	 */
	public function getStartTime()
	{
		return $this->getInteger($this->data['starttime']);
	}

	/**
	 * Get End Time
	 *
	 * @return int Endtime
	 * @access public
	 */
	public function getEndTime()
	{
		return $this->getInteger($this->data['endtime']);
	}

	/**
	 * Get route id
	 *
	 * @return int route id
	 * @access public
	 */
	public function getRouteId()
	{
		return $this->getInteger($this->data['route_id']);
	}

	/**
	 * Get work id
	 *
	 * @return int work id
	 * @access public
	 */
	public function getWorkId()
	{
		return $this->getInteger($this->data['work_id']);
	}

	/**
	 * Get Status
	 *
	 * @return int Status
	 * @access public
	 */
	public function getStatus()
	{
		return $this->getInteger($this->data['status']);
	}
}
