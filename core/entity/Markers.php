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
class Markers extends abstractEntity
{
	/**
	* All of fields of this objects
	*
	**/
	protected static $fields = array(
		'id'				    => 'integer',
		'name'				    => 'string',
		'x'			            => 'integer',
        'y'                     => 'integer',
        'type'                  => 'integer',
        'prvnce_id'             => 'integer',
	);

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned = array(
		'id',
        'type',
        'prvnce_id',
	);

	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_markers_table;

   /**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface	$db						Database object
	* @param string								$consim_markers_table	Name of the table used to store data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db, $consim_markers_table)
	{
		$this->db = $db;
		$this->consim_markers_table = $consim_markers_table;
	}

	/**
	* Insert the Data for the first time
	*
	* Will throw an exception if the data was already inserted (call save() instead)
	*
	* @return News $this object for chaining calls; load()->set()->save()
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
		$sql = 'INSERT INTO ' . $this->consim_markers_table . ' ' . $this->db->sql_build_array('INSERT', $this->data);
		$this->db->sql_query($sql);

		// Set the id using the id created by the SQL insert
		$this->data['id'] = (int) $this->db->sql_nextid();

		return $this;
	}

	/**
	* Load the data from the database for this object
	*
	* @param int $id user identifier
	* @return Markers $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function load($id)
	{
		$sql = 'SELECT id, name, x, y, type
			FROM ' . $this->consim_markers_table . '
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
	* Delete the entry with the given Id
	*
	* @return Markers $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function delete()
	{
		if (empty($this->data['id']))
		{
			// The data does not exist
			throw new \consim\core\exception\out_of_bounds('id');
		}

		$sql = 'DELETE FROM ' . $this->consim_markers_table . '
			WHERE id = ' . $this->getId();
		$this->db->sql_query($sql);

		return $this;
	}

	/**
	* Get Marker ID
	*
	* @return int $id
	* @access public
	*/
	public function getId()
	{
		return $this->getInteger($this->data['id']);
	}

	/**
	* Set Marker ID
	*
	* @param int $id
	* @return Markers
	* @access public
	*/
	public function setId($id)
	{
		return $this->setInteger('id',$id);
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
	* Set Name
    *
	* @param string $name
	* @return Markers
	* @access public
	*/
	public function setName($name)
	{
		return $this->setString('name',$name,255,0);
	}

	/**
	* Get X Coordinate
	*
	* @return int X
	* @access public
	*/
	public function getX()
	{
		return $this->getInteger($this->data['x']);
	}

    /**
	* Set X Coordinate
	*
	* @param int $x
	* @return Markers
	* @access public
	*/
	public function setX($x)
	{
		return $this->setInteger('x',$x);
	}

    /**
	* Get Y Coordinate
	*
	* @return int Y
	* @access public
	*/
	public function getY()
	{
		return $this->getInteger($this->data['y']);
	}

    /**
	* Set Y Coordinate
	*
	* @param int $y
	* @return Markers
	* @access public
	*/
	public function setY($y)
	{
		return $this->setInteger('y',$y);
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
	* @return Markers
	* @access public
	*/
	public function setType($type)
	{
		return $this->setInteger('type',$type);
	}
}
