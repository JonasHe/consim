<?php
/**
* @package ConSim for phpBB3.1
*
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*/

namespace consim\core\entity;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Entity for a single ressource
*/
class Action extends abstractEntity
{
	/**
	* All of fields of this objects
	*
	**/
	protected static $fields = array(
		'id'					=> 'integer',
		'user_id'				=> 'integer',
		'location_id'			=> 'integer',
		'starttime'				=> 'integer',
		'endtime'				=> 'integer',
		'route_id'				=> 'integer',
		'work_id'				=> 'integer',
		'successful_trials'		=> 'integer',
		'status'				=> 'integer',
	);

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned = array(
		'id',
		'user_id',
		'location_id',
		'starttime',
		'endtime',
		'route_id',
		'work_id',
		'successful_trials',
		'status',
	);

	/** @var ContainerInterface */
	protected $container;

	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_action_table;
	protected $consim_user_table;
	protected $consim_work_table;
	protected $consim_inventory_item_table;

	// work is active
	const active = 0;
	// work is finished
	const completed = 1;
	// user must be confirm the completed
	const mustConfirm = 2;

	/**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface	$db								Database object
	* @param ContainerInterface					$container						Service container interface
	* @param string								$consim_action_table			Name of the table used to store data
	* @param string								$consim_user_table				Name of the table used to store data
	* @param string								$consim_work_table				Name of the table used to store data
	* @param string								$consim_inventory_item_table	Name of the table used to store data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db,
								ContainerInterface $container,
								$consim_action_table,
								$consim_user_table,
								$consim_work_table,
								$consim_inventory_item_table)
	{
		$this->db = $db;
		$this->container = $container;
		$this->consim_action_table = $consim_action_table;
		$this->consim_user_table = $consim_user_table;
		$this->consim_work_table = $consim_work_table;
		$this->consim_inventory_item_table = $consim_inventory_item_table;
	}

	/**
	* Load the data from the database for this object
	*
	* @param int $id user identifier
	* @return self $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function load($id)
	{
		$sql = 'SELECT id, user_id, location_id, starttime, endtime, route_id, work_id, successful_trials, status
			FROM ' . $this->consim_action_table . '
			WHERE id = ' . (int) $id;
		$result = $this->db->sql_query($sql);
		$this->data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($this->data['id'] === false)
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
	 * @return self $this object for chaining calls; load()->set()->save()
	 * @access public
	 * @throws \consim\core\exception\out_of_bounds
	 */
	public function insert()
	{
		if (!empty($this->data['id']))
		{
			// The data already exists
			throw new \consim\core\exception\out_of_bounds('id');
		}

		// Make extra sure there is no id set
		unset($this->data['id']);

		// Insert the data to the database
		$sql = 'INSERT INTO ' . $this->consim_action_table . ' ' . $this->db->sql_build_array('INSERT', $this->data);
		$this->db->sql_query($sql);

		$this->data['id'] = (int) $this->db->sql_nextid();

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
	* @return int User ID
	* @access public
	*/
	public function getUserId()
	{
		return $this->getInteger($this->data['user_id']);
	}

	/**
	 * Set User Id
	 *
	 * @param int $user_id
	 * @return self
	 * @access public
	 */
	public function setUserId($user_id)
	{
		return $this->setInteger('user_id', $user_id);
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
	 * Set location id
	 *
	 * @param int $location_id location id
	 * @return self
	 * @access public
	 */
	public function setLocationId($location_id)
	{
		return $this->setInteger('location_id', $location_id);
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
	 * Set Start Time
	 *
	 * @param int $starttime
	 * @return self
	 * @access public
	 */
	public function setStartTime($starttime)
	{
		return $this->setInteger('starttime', $starttime);
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
	 * Set End Time
	 *
	 * @param int $endtime
	 * @return self
	 * @access public
	 */
	public function setEndTime($endtime)
	{
		return $this->setInteger('endtime', $endtime);
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
	 * Set route id and reset work id to 0
	 *
	 * @param int $route_id
	 * @return self
	 * @access public
	 */
	public function setRouteId($route_id)
	{
		$this->setInteger('work_id', 0);
		return $this->setInteger('route_id', $route_id);
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
	 * Set work id and reset route id to 0
	 *
	 * @param int $work_id
	 * @return self
	 * @access public
	 */
	public function setWorkId($work_id)
	{
		$this->setInteger('route_id', 0);
		return $this->setInteger('work_id', $work_id);
	}

	/**
	 * Get successful trials
	 *
	 * @return int successful trials
	 * @access public
	 */
	public function getSuccessfulTrials()
	{
		return $this->getInteger($this->data['successful_trials']);
	}

	/**
	 * Set successful trials
	 *
	 * @param $successful_trials
	 * @return self
	 * @throws \consim\core\exception\out_of_bounds
	 */
	public function setSuccessfulTrials($successful_trials)
	{
		return $this->setInteger('successful_trials', $successful_trials);
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

	/**
	 * Action done with auto save
	 *
	 * @access public
	 * @throws \consim\core\exception\out_of_bounds
	 */
	public function done()
	{
		//is it really ready or it is already done?
		if($this->data['endtime'] > time() || $this->data['status'] == 1)
		{
			throw new \consim\core\exception\out_of_bounds('time');
		}

		//it is traveling
		if($this->data['route_id'] > 0)
		{
			$route = $this->container->get('consim.core.entity.route')->load($this->data['route_id']);
			if($this->data['location_id'] == $route->getStartLocationId())
			{
				$end_location_id = $route->getEndLocationId();
			}
			else
			{
				$end_location_id = $route->getStartLocationId();
			}

			//Action is done
			$sql = 'UPDATE ' . $this->consim_action_table . '
			SET status = 1
			WHERE id = ' . $this->data['id'];
			$this->db->sql_query($sql);

			//User is free and at the new location
			$sql = 'UPDATE ' . $this->consim_user_table . '
			SET active = 0, location_id = '. $end_location_id .'
			WHERE user_id = ' . $this->data['user_id'];
			$this->db->sql_query($sql);

			$this->data['status'] = 1;

			//finished
			return null;
		}

		//it is working
		if($this->data['work_id'] > 0)
		{
			//Action is done
			$sql = 'UPDATE ' . $this->consim_action_table . '
			SET status = '. self::mustConfirm .'
			WHERE id = ' . $this->data['id'];
			$this->db->sql_query($sql);

			$this->data['status'] = 2;

			//finished
			return null;
		}


	}

	public function userDone()
	{
		//is it really ready or it is already done?
		if($this->data['endtime'] > time() || $this->data['status'] != 2)
		{
			throw new \consim\core\exception\out_of_bounds('time');
		}

		//it is traveling
		if($this->data['route_id'] > 0)
		{
			//nothing to do
			return null;
		}

		//it is working
		if($this->data['work_id'] > 0)
		{
			//get infos about work and inventory items
			$sql = 'SELECT w.output_id, w.output_value, w.condition_value, w.experience_points, 
					i.value AS currentValue, s.value AS user_skill
				FROM '. $this->consim_work_table .' w
				LEFT JOIN phpbb_consim_user_skills s ON s.skill_id = w.condition_id AND s.user_id = '. $this->data['user_id'] .'
				LEFT JOIN '. $this->consim_inventory_item_table .' i ON i.item_id = w.output_id AND i.user_id = '. $this->data['user_id'] .'
				WHERE w.id = '. $this->getWorkId() ;
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			$result = Work::trialsNumber;
			if($row['condition_value'] > 0)
			{
				$result = $this->calculateResult($row['user_skill']);
			}

			//Action is done
			$sql = 'UPDATE ' . $this->consim_action_table . '
			SET status = '. self::completed .', successful_trials = '. $result .'
			WHERE id = ' . $this->data['id'];
			$this->db->sql_query($sql);
			$this->data['status'] = 1;
			$this->data['successful_trials'] = $result;

			//this work is not successful - no reward :(
			if($result < Work::neededSuccessfulTrials)
			{
				//terminate
				//User is free
				$sql = 'UPDATE ' . $this->consim_user_table . '
					SET active = 0
					WHERE user_id = ' . $this->data['user_id'];
				$this->db->sql_query($sql);

				return null;
			}

			//User is free
			$sql = 'UPDATE ' . $this->consim_user_table . '
			SET active = 0, experience_points = experience_points + '. $row['experience_points'] .'
			WHERE user_id = ' . $this->data['user_id'];
			$this->db->sql_query($sql);

			//no output :(
			if($row['output_id'] == 0)
			{
				return null;
			}

			if(isset($row['currentValue']))
			{
				//Item is present
				//set output to user
				$sql = 'UPDATE ' . $this->consim_inventory_item_table . '
					SET  value = value + '. $row['output_value'] .'
					WHERE user_id = '. $this->data['user_id'] .'
					AND item_id = '. $row['output_id'];
				$this->db->sql_query($sql);
			}
			else
			{
				//Item is new in this inventory
				//set output to user
				$sql = 'INSERT INTO ' . $this->consim_inventory_item_table . ' (user_id, item_id, value)
					VALUES  ('. $this->data['user_id'] .', '. $row['output_id'] .', '. $row['output_value'] .')';
				$this->db->sql_query($sql);
			}
		}

		return null;
	}

	private function calculateResult($user_skill)
	{
		$result = 0;
		for($i = 0; $i < Work::trialsNumber; $i++)
		{
			$rand = mt_rand(0, 100);
			if($rand <= $user_skill)
			{
				$result++;
			}
		}

		return $result;
	}
}
