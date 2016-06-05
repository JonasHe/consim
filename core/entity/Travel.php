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
class Travel extends Action
{
	/**
	* All of fields of this objects
	*
	**/
	protected static $fields = array(
		'id'						=> 'integer',
		'user_id'                   => 'integer',
		'starttime'				    => 'integer',
		'endtime'				    => 'integer',
		'start_location_id'         => 'integer',
		'end_location_id'           => 'integer',
		'status'					=> 'boolean',
	);

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned = array(
		'id',
		'user_id',
		'starttime',
		'endtime',
		'start_location_id',
		'end_location_id',
		'status',
	);

	protected $data;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_action_table;
	protected $consim_travel_table;
	protected $consim_user_table;

	/**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface    $db                          Database object
	* @param string                               $consim_action_table         Name of the table used to store data
	* @param string                               $consim_travel_table         Name of the table used to store data
	* @param string                               $consim_user_table           Name of the table used to store data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db, $consim_action_table, $consim_travel_table, $consim_user_table)
	{
		$this->db = $db;
		$this->consim_action_table = $consim_action_table;
		$this->consim_travel_table = $consim_travel_table;
		$this->consim_user_table = $consim_user_table;
	}

	/**
	* Insert the Data for the first time
	*
	* Will throw an exception if the data was already inserted (call save() instead)
	*
	* @return object $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function insert($user_id, $starttime, $endtime, $start_location_id, $end_location_id)
	{
		$this->setInteger('user_id', $user_id);
		$this->setInteger('starttime', $starttime);
		$this->setInteger('endtime', $endtime);
		$this->setInteger('start_location_id', $start_location_id);
		$this->setInteger('end_location_id', $end_location_id);


		if (!empty($this->data['id']))
		{
			// The data already exists
			throw new \consim\core\exception\out_of_bounds('id');
		}

		// Make extra sure there is no id set
		unset($this->data['id']);

		//TODO: Hmmm why???
		$travel = array(
			'start_location_id'          => $this->data['start_location_id'],
			'end_location_id'            => $this->data['end_location_id'],
		);

		// Insert the data to the database
		$sql = 'INSERT INTO ' . $this->consim_travel_table . ' ' . $this->db->sql_build_array('INSERT', $travel);
		$this->db->sql_query($sql);

		$travel_id = (int) $this->db->sql_nextid();

		$action = array(
			'user_id'                   => $this->data['user_id'],
			'starttime'				    => $this->data['starttime'],
			'endtime'				    => $this->data['endtime'],
			'travel_id'                 => $travel_id,
			'status'					=> 0,
		);

		// Insert the data to the database
		$sql = 'INSERT INTO ' . $this->consim_action_table . ' ' . $this->db->sql_build_array('INSERT', $action);
		$this->db->sql_query($sql);

		//User is now active
		$sql = 'UPDATE ' . $this->consim_user_table . '
			SET active = 1
			WHERE user_id = ' . $this->data['user_id'];
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
	* @return int UserID
	* @access public
	*/
	public function getUserId()
	{
		return $this->getInteger($this->data['user_id']);
	}

	/**
	* Get Starttime
	*
	* @return int Starttime
	* @access public
	*/
	public function getStartTime()
	{
		return $this->getInteger($this->data['starttime']);
	}

	/**
	* Get Endtime
	*
	* @return int Endtime
	* @access public
	*/
	public function getEndTime()
	{
		return $this->getInteger($this->data['endtime']);
	}

	/**
	* Get Status
	*
	* @return bool Status
	* @access public
	*/
	public function getStatus()
	{
		return $this->getInteger($this->data['status']);
	}

	/**
	* Travel done
	*
	* @return Action $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function done()
	{
		if($this->data['endtime'] > time() || $this->data['status'] === 1)
		{
			throw new \consim\core\exception\out_of_bounds($integer);
		}

		//User is free
		$sql = 'UPDATE ' . $this->consim_action_table . '
			SET status = 1
			WHERE id = ' . $this->data['id'];
		$this->db->sql_query($sql);

		//User is free and at the new location
		$sql = 'UPDATE ' . $this->consim_user_table . '
			SET active = 0, location_id = '. $this->data['end_location_id'] .'
			WHERE user_id = ' . $this->data['user_id'];
		$this->db->sql_query($sql);

		return $this;
	}
}
