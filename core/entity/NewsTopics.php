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
class NewsTopics extends abstractEntity
{
	/**
	* All of fields of this objects
	*
	**/
	protected static $fields = array(
		'topic_id'				=> 'integer',
		'topic_name'			=> 'string',
	);

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned = array(
		'topic_id',
	);

	protected $data;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_news_topics_table;

   /**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface	$db							Database object
	* @param string								$consim_news_topics_table	Name of the table used to store data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db, $consim_news_topics_table)
	{
		$this->db = $db;
		$this->consim_news_topics_table = $consim_news_topics_table;
	}

	/**
	* Load the data from the database for this object
	*
	* @param int $id user identifier
	* @return NewsTopics $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function load($id)
	{
		$sql = 'SELECT topic_id, topic_name
			FROM ' . $this->consim_news_topics_table . '
			WHERE topic_id = ' . (int) $id;
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
	* @return NewsTopics $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function insert()
	{
		if (!empty($this->data['topic_id']))
		{
			// The data already exists
			throw new \consim\core\exception\out_of_bounds('id');
		}

		// Make extra sure there is no id set
		unset($this->data['topic_id']);
		
		// Insert the data to the database
		$sql = 'INSERT INTO ' . $this->consim_news_topics_table . ' ' . $this->db->sql_build_array('INSERT', $this->data);
		$this->db->sql_query($sql);

		// Set the id using the id created by the SQL insert
		$this->data['topic_id'] = (int) $this->db->sql_nextid();

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
		if (empty($this->data['topic_id']))
		{
			// The data does not exist
			throw new \consim\core\exception\out_of_bounds('id');
		}

		$sql = 'UPDATE ' . $this->consim_news_topics_table . '
			SET ' . $this->db->sql_build_array('UPDATE', $this->data) . '
			WHERE topic_id = ' . $this->getId();
		$this->db->sql_query($sql);

		return $this;
	}
	
	/**
	* Delete the entry with the given Id
	*
	* @return NewsTopics $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function delete()
	{
		if (empty($this->data['topic_id']))
		{
			// The data does not exist
			throw new \consim\core\exception\out_of_bounds('id');
		}

		$sql = 'DELETE FROM ' . $this->consim_news_topics_table . '
			WHERE topic_id = ' . $this->getId();
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
		return $this->getInteger($this->data['topic_id']);
	}
	
	/**
	* Set News ID
	*
	* @param int $id
	* @return NewsTopics
	* @access public
	*/
	public function setId($id)
	{
		return $this->setInteger('topic_id',$id);
	}
	
	/**
	* Get News Content
	*
	* @return string Content
	* @access public
	*/
	public function getTopicName()
	{
		return $this->getString($this->data['topic_name']);
	}
	
	/**
	* Set topic name
	*
	* @param string $topic
	* @return NewsTopics $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setTopicName($topic)
	{
		return $this->setString('topic_name', $topic, 255, 2);
	}
}
