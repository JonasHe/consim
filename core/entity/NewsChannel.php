<?php
/**
* @package ConSim for phpBB3.1
*
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*/

namespace consim\core\entity;

/**
* Entity for news entries
*/
class NewsChannel extends abstractEntity
{
	/**
	* All of fields of this objects
	*
	**/
	protected static $fields = array(
    	'channel_id'				=> 'integer',
      	'group_id'				    => 'integer',
        'channel_name'				=> 'string',
        'vRefresh'                  => 'integer',
        'background_color'          => 'string',
		'color'              		=> 'string',
	);

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned = array(
      	'channel_id',
        'group_id',
        'vRefresh',
	);

	protected $data;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_channel_table;

   /**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface    $db                     Database object
	* @param string                               $consim_province_table  Name of the table used to store data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db, $consim_channel_table)
	{
		$this->db = $db;
		$this->consim_channel_table = $consim_channel_table;
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
		$sql = 'SELECT channel_id, group_id, channel_name, vRefresh, background_color, color
			FROM ' . $this->consim_channel_table . '
			WHERE channel_id = ' . (int) $id;
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
	* @return object $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function insert()
	{
        if (!empty($this->data['channel_id']))
		{
			// The data already exists
			throw new \consim\core\exception\out_of_bounds('id');
		}

		// Make extra sure there is no id set
		unset($this->data['channel_id']);

		// Insert the data to the database
		$sql = 'INSERT INTO ' . $this->consim_channel_table . ' ' . $this->db->sql_build_array('INSERT', $this->data);
		$this->db->sql_query($sql);

        // Set the id using the id created by the SQL insert
		$this->data['channel_id'] = (int) $this->db->sql_nextid();

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
		if (empty($this->data['channel_id']))
		{
			// The data does not exist
			throw new \consim\core\exception\out_of_bounds('id');
		}

		$sql = 'UPDATE ' . $this->consim_channel_table . '
			SET ' . $this->db->sql_build_array('UPDATE', $this->data) . '
			WHERE channel_id = ' . $this->getId();
		$this->db->sql_query($sql);

		return $this;
	}
	
	/**
	* Delete the entry with the given Id
	*
	* @return object $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function delete()
	{
		if (empty($this->data['channel_id']))
		{
			// The data does not exist
			throw new \consim\core\exception\out_of_bounds('id');
		}

		$sql = 'DELETE FROM ' . $this->consim_channel_table . '
			WHERE channel_id = ' . $this->getId();
		$this->db->sql_query($sql);

		return $this;
	}

	/**
	* Get channel ID
	*
	* @return int ID
	* @access public
	*/
	public function getId()
	{
		return $this->getInteger($this->data['channel_id']);
	}
	
	/**
	* Set channel ID
	*
	* @param int id
	* @return int ID
	* @access public
	*/
	public function setId($id)
	{
		return $this->setInteger('channel_id',$id);
	}
	
	/**
	* Get group ID
	*
	* @return int ID
	* @access public
	*/
	public function getGroupId()
	{
		return $this->getInteger($this->data['group_id']);
	}
	
	/**
	* Set group ID
	*
	* @param int id
	* @return int ID
	* @access public
	*/
	public function setGroupId($id)
	{
		return $this->setInteger('group_id',$id);
	}

    /**
	* Get Name
	*
	* @return string Name
	* @access public
	*/
	public function getChannelName()
	{
		return $this->getString($this->data['channel_name']);
	}
	
	/**
	* Set Name
	*
	* @param string name
	* @return string Name
	* @access public
	*/
	public function setChannelName($name)
	{
		return $this->setString('channel_name',$name, 255, 2);
	}
	
	/**
	* Get vRefresh
	*
	* @return bool
	* @access public
	*/
	public function getvRefresh()
	{
		return $this->getInteger($this->data['vRefresh']);
	}
	
	/**
	* Set vRefresh
	*
	* @param int refresh
	* @return bool
	* @access public
	*/
	public function setvRefresh($refresh)
	{
		return $this->setInteger('vRefresh',$refresh);
	}
	
	/**
	* Get Background
	*
	* @return string Background
	* @access public
	*/
	public function getBackground()
	{
		return $this->getString($this->data['background_color']);
	}
	
	/**
	* Set Background
	*
	* @param string color
	* @return string Background
	* @access public
	*/
	public function setBackground($color)
	{
		return $this->setString('background_color', $color, 255, 3);
	}
	
	/**
	* Get Border
	*
	* @return string Border
	* @access public
	*/
	public function getBorder()
	{
		return $this->getString($this->data['color']);
	}
	
	/**
	* Set Border
	*
	* @param string color
	* @return string Border
	* @access public
	*/
	public function setBorder($color)
	{
		return $this->setString('color', $color, 255, 3);
	}
}
