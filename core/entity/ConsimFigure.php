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
		'groups'            	    => 'string',
		'value'						=> 'string',
		'name'				       	=> 'string',
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
