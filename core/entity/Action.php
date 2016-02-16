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
*/
class Action extends abstractEntity
{
	/**
	* All of fields of this objects
	*
	**/
	protected static $fields = array(
    	'id'						=> 'integer',
      	'user_id'                   => 'integer',
      	'type'						=> 'string',
      	'time'					    => 'integer',
        'status'					=> 'boolean',
	);

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned = array(
      	'id',
        'user_id',
        'time',
	);

	protected $data;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_action_table;

    /**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface    $db                          Database object
    * @param ContainerInterface                	  $container       	           Service container interface
    * @param string                               $consim_action_table       Name of the table used to store data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db, $consim_action_table)
	{
		$this->db = $db;
		$this->consim_action_table = $consim_action_table;
	}

	/**
	* Load the data from the database for this object
	*
	* @param int $id user identifier
	* @return object $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\user\exception\out_of_bounds
	*/
	public function load($id)
	{
		$sql = 'SELECT id, user_id, type, time, status
			FROM ' . $this->consim_action_table . '
			WHERE l.id = ' . (int) $id;
		$result = $this->db->sql_query($sql);
		$this->data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($row === false)
		{
			throw new \consim\core\exception\out_of_bounds('id');
		}

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
	* Set User ID
	*
	* @param int $user_id
	* @return Action $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setUserId($user_id)
	{
		return $this->setInteger('user_id', $user_id);
	}

    /**
	* Get Type
	*
	* @return string Type
	* @access public
	*/
	public function getType()
	{
		return $this->getString($this->data['type']);
	}

    /**
	* Set Type
	*
	* @param string $type
	* @return Action $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setType($type)
	{
		return $this->setString('type', $type, 1, 1);
	}

    /**
	* Get Start Time
	*
	* @return int Time
	* @access public
	*/
	public function getTime()
	{
		return $this->getInteger($this->data['time']);
	}

    /**
	* Set Time
	*
	* @param int $time
	* @return Action $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setTime($time)
	{
		return $this->setInteger('time', $time);
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
	* Set Status
	*
	* @param int $status
	* @return Action $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setStatus($status)
	{
		return $this->setInteger('status', $status);
	}
}
