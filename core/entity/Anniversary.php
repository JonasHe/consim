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
* Entity for news entries
*/
class Anniversary extends abstractEntity
{
	/**
	* All of fields of this objects
	*
	**/
	protected static $fields = array(
		'anniversary_id'			=> 'integer',
		'day'						=> 'integer',
		'month'						=> 'integer',
		'year'						=> 'integer',
		'event'						=> 'string',
		'link'						=> 'string',
	);

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned = array(
		'anniversary_id',
		'day',
		'month',
		'year',
	);

	protected $data;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_anniversary_table;

   /**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface    $db					Database object
	* @param string                               $consim_anniversary_table	Name of the table used to store data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db, $consim_anniversary_table)
	{
		$this->db = $db;
		$this->consim_anniversary_table = $consim_anniversary_table;
	}

	/**
	* Load the data from the database for this object
	*
	* @param int $id user identifier
	* @return Anniversary $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function load($id)
	{
		$sql = 'SELECT anniversary_id, day, month, year, event, link
			FROM ' . $this->consim_anniversary_table . '
			WHERE anniversary_id = ' . (int) $id;
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
	* Insert the Data for the first time
	*
	* Will throw an exception if the data was already inserted (call save() instead)
	*
	* @return Anniversary $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function insert()
	{
		if (!empty($this->data['anniversary_id']))
		{
			// The data already exists
			throw new \consim\core\exception\out_of_bounds('id');
		}

		// Make extra sure there is no id set
		unset($this->data['anniversary_id']);

		// Insert the data to the database
		$sql = 'INSERT INTO ' . $this->consim_anniversary_table . $this->db->sql_build_array('INSERT', $this->data);
		$this->db->sql_query($sql);

		// Set the id using the id created by the SQL insert
		$this->data['anniversary_id'] = (int) $this->db->sql_nextid();

		return $this;
	}

	/**
	* Save the current settings to the database
	*
	* This must be called before closing or any changes will not be saved!
	* If adding a data (saving for the first time), you must call insert() or an exeception will be thrown
	*
	* @return Anniversary $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function save()
	{
		if (empty($this->data['anniversary_id']))
		{
			// The data does not exist
			throw new \consim\core\exception\out_of_bounds('id');
		}

		$sql = 'UPDATE ' . $this->consim_anniversary_table . '
			SET ' . $this->db->sql_build_array('UPDATE', $this->data) . '
			WHERE anniversary_id = ' . $this->getId();
		$this->db->sql_query($sql);

		return $this;
	}
	
	/**
	* Delete the entry with the given Id
	*
	* @return Anniversary $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function delete()
	{
		if (empty($this->data['anniversary_id']))
		{
			// The data does not exist
			throw new \consim\core\exception\out_of_bounds('id');
		}

		$sql = 'DELETE FROM ' . $this->consim_anniversary_table . '
			WHERE anniversary_id = ' . $this->getId();
		$this->db->sql_query($sql);

		return $this;
	}

	/**
	* Get Anniversary ID
	*
	* @return int ID
	* @access public
	*/
	public function getId()
	{
		return $this->getInteger($this->data['anniversary_id']);
	}
	
	/**
	* Set Anniversary ID
	*
	* @param int $id
	* @return Anniversary
	* @access public
	*/
	public function setId($id)
	{
		return $this->setInteger('anniversary_id',$id);
	}
	
	/**
	* Get Anniversary Day
	*
	* @return int Day
	* @access public
	*/
	public function getDay()
	{
		return $this->getInteger($this->data['day']);
	}
	
	/**
	* Set Anniversary Day
	*
	* @param int $id
	* @return Anniversary
	* @access public
	*/
	public function setDay($id)
	{
		return $this->setInteger('day',$id);
	}

	/**
	* Get Anniversary Month
	*
	* @return int Month
	* @access public
	*/
	public function getMonth()
	{
		return $this->getInteger($this->data['month']);
	}
	
	/**
	* Set Anniversary Month
	*
	* @param int $id
	* @return Anniversary
	* @access public
	*/
	public function setMonth($id)
	{
		return $this->setInteger('month',$id);
	}

	/**
	* Get Anniversary Year
	*
	* @return int Year
	* @access public
	*/
	public function getYear()
	{
		return $this->getInteger($this->data['year']);
	}
	
	/**
	* Set Anniversary Year
	*
	* @param int $id
	* @return Anniversary
	* @access public
	*/
	public function setYear($id)
	{
		return $this->setInteger('year',$id);
	}

	/**
	* Get Event
	*
	* @return string Content
	* @access public
	*/
	public function getEvent()
	{
		return $this->getString($this->data['event']);
	}
	
	/**
	* Set Event
	*
	* @param string $content
	* @return Anniversary
	* @access public
	*/
	public function setEvent($content)
	{
		return $this->setString('event',$content,255,0);
	}

	/**
	* Get Link
	*
	* @return string Content
	* @access public
	*/
	public function getLink()
	{
		return $this->getString($this->data['link']);
	}
	
	/**
	* Set Link
	*
	* @param string $content
	* @return Anniversary
	* @access public
	*/
	public function setLink($content)
	{
		return $this->setString('link',$content,255,0);
	}
}
