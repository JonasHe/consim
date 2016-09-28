<?php
/**
* @package ConSim for phpBB3.1
*
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @copyright (c) 2016 Jonas Heitmann (kommodoree@googlemail.com)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*/

namespace consim\core\entity;

/**
* Entity for a single ressource
*/
class Roads extends abstractEntity
{
	/**
	* All of fields of this objects
	*
	**/
	protected static $fields = array(
		'id'				    => 'integer',
		'title'					=> 'string',
		'blocked'				=> 'integer',
		'type'			        => 'integer',
        'prvnce_id'             => 'integer',
	);

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned = array(
		'id',
		'blocked',
        'type',
        'prvnce_id',
	);

	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_roads_table;

   /**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface	$db						Database object
	* @param string								$consim_roads_table	    Name of the table used to store data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db, $consim_roads_table)
	{
		$this->db = $db;
		$this->consim_roads_table = $consim_roads_table;
	}

	/**
	* Load the data from the database for this object
	*
	* @param int $id user identifier
	* @return Roads $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function load($id)
	{
		$sql = 'SELECT id, title, blocked, type
			FROM ' . $this->consim_roads_table . '
			WHERE id = ' . (int) $id;
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
	* Save the current settings to the database
	*
	* This must be called before closing or any changes will not be saved!
	* If adding a data (saving for the first time), you must call insert() or an exeception will be thrown
	*
	* @return NewsTopics $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function save()
	{
		if (empty($this->data['id']))
		{
			// The data does not exist
			throw new \consim\core\exception\out_of_bounds('id');
		}

		$sql = 'UPDATE ' . $this->consim_roads_table . '
			SET ' . $this->db->sql_build_array('UPDATE', $this->data) . '
			WHERE id = ' . $this->getId();
		$this->db->sql_query($sql);

		return $this;
	}

	/**
	* Get Road ID
	*
	* @return int $id
	* @access public
	*/
	public function getId()
	{
		return $this->getInteger($this->data['id']);
	}

	/**
	* Set Road ID
	*
	* @param int $id The Id of the road
	* @return int $id
	* @access public
	*/
	public function setId($id)
	{
		return $this->setInteger('id', $id);
	}

	/**
	* Get Title
	*
	* @return string Title
	* @access public
	*/
	public function getTitle()
	{
		return $this->getString($this->data['title']);
	}

	/**
	* Get Blocked
	*
	* @return int Blocked
	* @access public
	*/
	public function getBlocked()
	{
		return $this->getInteger($this->data['blocked']);
	}

    /**
	* Set Blocked
	*
	* @param int $blocked
	* @return Roads
	* @access public
	*/
	public function setBlocked($blocked)
	{
		return $this->setInteger('blocked',$blocked);
	}

    /**
	* Get Type
	*
	* @return int $type
	* @access public
	*/
	public function getType()
	{
		return $this->getInteger($this->data['type']);
	}

	/**
	* Set Type
	*
	* @param int $type
	* @return Roads
	* @access public
	*/
	public function setType($type)
	{
		return $this->setInteger('type',$type);
	}
}
