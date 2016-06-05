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
class News extends abstractEntity
{
	/**
	* All of fields of this objects
	*
	**/
	protected static $fields = array(
		'news_id'					=> 'integer',
		'channel_id'				=> 'integer',
		'topic_id'					=> 'integer',
		'content'					=> 'string',
	);

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned = array(
		'news_id',
		'channel_id',
		'topic_id',
	);

	protected $data;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_news_table;

   /**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface    $db                     Database object
	* @param string                               $consim_province_table  Name of the table used to store data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db, $consim_news_table)
	{
		$this->db = $db;
		$this->consim_news_table = $consim_news_table;
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
		$sql = 'SELECT news_id, channel_id, content
			FROM ' . $this->consim_news_table . '
			WHERE news_id = ' . (int) $id;
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
		if (!empty($this->data['news_id']))
		{
			// The data already exists
			throw new \consim\core\exception\out_of_bounds('id');
		}

		// Make extra sure there is no id set
		unset($this->data['news_id']);

		// Insert the data to the database
		$sql = 'INSERT INTO ' . $this->consim_news_table . ' ' . $this->db->sql_build_array('INSERT', $this->data);
		$this->db->sql_query($sql);

		// Set the id using the id created by the SQL insert
		$this->data['news_id'] = (int) $this->db->sql_nextid();

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
		if (empty($this->data['news_id']))
		{
			// The data does not exist
			throw new \consim\core\exception\out_of_bounds('id');
		}

		$sql = 'UPDATE ' . $this->consim_news_table . '
			SET ' . $this->db->sql_build_array('UPDATE', $this->data) . '
			WHERE news_id = ' . $this->getId();
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
		if (empty($this->data['news_id']))
		{
			// The data does not exist
			throw new \consim\core\exception\out_of_bounds('id');
		}

		$sql = 'DELETE FROM ' . $this->consim_news_table . '
			WHERE news_id = ' . $this->getId();
		$this->db->sql_query($sql);

		return $this;
	}

	/**
	* Get News ID
	*
	* @return int ID
	* @access public
	*/
	public function getId()
	{
		return $this->getInteger($this->data['news_id']);
	}
	
	/**
	* Set News ID
	*
	* @param int ID
	* @return int ID
	* @access public
	*/
	public function setId($id)
	{
		return $this->setInteger('news_id',$id);
	}

	/**
	* Get Channel ID
	*
	* @return int ID
	* @access public
	*/
	public function getChannelId()
	{
		return $this->getInteger($this->data['channel_id']);
	}
	
	/**
	* Set Channel ID
	*
	* @param int ID
	* @return int ID
	* @access public
	*/
	public function setChannelId($id)
	{
		return $this->setInteger('channel_id',$id);
	}
	
	/**
	* Get Topic ID
	*
	* @return int ID
	* @access public
	*/
	public function getTopicId()
	{
		return $this->getInteger($this->data['topic_id']);
	}
	
	/**
	* Set Topic ID
	*
	* @param int ID
	* @return int ID
	* @access public
	*/
	public function setTopicId($id)
	{
		return $this->setInteger('topic_id',$id);
	}
	
	/**
	* Get News Content
	*
	* @return string Content
	* @access public
	*/
	public function getContent()
	{
		return $this->getString($this->data['content']);
	}
	
	/**
	* Set News Content
	*
	* @param string content
	* @return string Content
	* @access public
	*/
	public function setContent($content)
	{
		return $this->setString('content',$content,255,0);
	}
}
