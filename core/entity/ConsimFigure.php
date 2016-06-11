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
class ConsimFigure extends abstractEntity
{
	/**
	* All of fields of this objects
	*
	**/
	protected static $fields = array(
		'id'						=> 'integer',
<<<<<<< HEAD
		'groups'            	    => 'string',
		'value'						=> 'string',
		'name'				       	=> 'string',
=======
		'description'    	        => 'string',
		'value'						=> 'string',
		'translate'					=> 'string',
>>>>>>> refs/remotes/origin/master
	);

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned = array(
		'id',
	);

	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_person_table;

   /**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface    $db                   Database object
	* @param string                               $consim_person_table  Name of the table used to store consim user data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db, $consim_person_table)
	{
		$this->db = $db;
		$this->consim_person_table = $consim_person_table;
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
		$sql = 'SELECT id, groups, value, name
			FROM ' . $this->consim_person_table . '
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
<<<<<<< HEAD
=======
	* Insert the Data for the first time
	*
	* Will throw an exception if the data was already inserted (call save() instead)
	*
	* @return object $this object for chaining calls; load()->set()->save()
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
		$sql = 'INSERT INTO ' . $this->consim_person_table . ' ' . $this->db->sql_build_array('INSERT', $this->data);
		$this->db->sql_query($sql);

		// Set the id using the id created by the SQL insert
		$this->data['id'] = (int) $this->db->sql_nextid();

		return $this;
	}

	/**
	* Save the current settings to the database
	*
	* This must be called before closing or any changes will not be saved!
	* If adding a data (saving for the first time), you must call insert() or an exeception will be thrown
	*
	* @return object $this object for chaining calls; load()->set()->save()
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

		$sql = 'UPDATE ' . $this->consim_person_table . '
			SET ' . $this->db->sql_build_array('UPDATE', $this->data) . '
			WHERE id = ' . $this->getId();
		$this->db->sql_query($sql);

		return $this;
	}

	/**
>>>>>>> refs/remotes/origin/master
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
	* Get Groups
	*
	* @return string Groups
	* @access public
	*/
	public function getGroups()
	{
		return $this->getString($this->data['groups']);
	}

   /**
	* Get Value
	*
	* @return string Value
	* @access public
	*/
	public function getValue()
	{
		return $this->getString($this->data['value']);
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
}
