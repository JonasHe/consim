<?php

/**
*
* @package ConSim for phpBB3.1
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @copyright (c) 2016 Jonas Heitmann (kommodoree@googlemail.com)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace consim\core\controller\acp;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* News controller
*/
class News
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $controller_helper;

	/** @var ContainerInterface */
	protected $container;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/**
	* Constructor
	*
	* @param \phpbb\config\config				$config			Config object
	* @param \phpbb\controller\helper			$helper			Controller helper object
	* @param ContainerInterface					$container		Service container interface
	* @param \phpbb\user						$user			User object
	* @param \phpbb\template\template			$template		Template object
	* @param \phpbb\request\request				$request		Request object
	* @param \phpbb\db\driver\driver_interface	$db				Database object
	* @return News
	* @access public
	*/
	public function __construct(\phpbb\config\config $config,
								ContainerInterface $container,
								\phpbb\controller\helper $helper,
								\phpbb\user $user,
								\phpbb\template\template $template,
								\phpbb\request\request $request,
								\phpbb\db\driver\driver_interface $db)
	{
		$this->config = $config;
		$this->container = $container;
		$this->helper = $helper;
		$this->user = $user;
		$this->template = $template;
		$this->request = $request;
		$this->db = $db;
	}

	/**
	* Prüfe die eingehenden Daten
	*
	* @param string[] $errors
	* @param string[] $fields All required fields
	* @return null
	* @access private
	*/
	private function check_data(&$errors, $fields)
	{
		// Add language file
		$this->user->add_lang_ext('consim/core', 'exceptions');

		foreach($fields as $value)
		{
			if(trim($this->request->variable($value, '')) == '')
			{
				$errors[] = $this->user->lang('EXCEPTION_FIELD_MISSING');
			}
		}
	}
	
	/**
	* Display all channels and news
	*
	* @return null
	* @access public
	*/
	public function news_overview()
	{
		// Catch all channels from the database
		$csql = 'SELECT c.channel_id, g.group_name, c.channel_name, c.vRefresh FROM phpbb_consim_news_channel c INNER JOIN '.GROUPS_TABLE.' g ON c.group_id = g.group_id';
		$cresult = $this->db->sql_query($csql);
		while($crow = $this->db->sql_fetchrow($cresult))
		{
			$this->template->assign_block_vars('allChannels', array(
				'GROUP' 		=> (isset($this->user->lang['G_'.$crow['group_name']])) ? $this->user->lang['G_'.$crow['group_name']] : $crow['group_name'],
				'CHANNELNAME' 	=> $crow['channel_name'],
				'VREFRESH' 		=> ($crow['vRefresh']==1) ? $this->user->lang['YES'] : $this->user->lang['NO'],
				'EDITCHANNEL' 	=> build_url("action")."&amp;action=edit_channel&amp;channel_id=".$crow['channel_id'],
			));
		}
		
		// Add link to add a channel
		$this->template->assign_var('ADDCHANNEL',build_url("action")."&amp;action=add_channel");
		
		// Catch all topics from the database
		$tsql = 'SELECT topic_id, topic_name FROM phpbb_consim_news_topics';
		$tresult = $this->db->sql_query($tsql);
		while($trow = $this->db->sql_fetchrow($tresult))
		{
			$this->template->assign_block_vars('allTopics', array(
				'TOPICID'		=> $trow['topic_id'],
				'DELETETOPIC' 	=> build_url("action").'&amp;action=delete_topic&amp;topic_id='.$trow['topic_id'],
				'NAME' 			=> $trow['topic_name'],
				
			));
		}
		
		// Catch all news from the database
		$nsql = "SELECT n.news_id, n.content, c.channel_name, t.topic_name FROM phpbb_consim_news n
			INNER JOIN phpbb_consim_news_channel c ON n.channel_id = c.channel_id
			INNER JOIN phpbb_consim_news_topics t ON n.topic_id = t.topic_id
			ORDER BY news_id ASC";
		$nresult = $this->db->sql_query($nsql);
		while ($nrow = $this->db->sql_fetchrow($nresult))
		{
			$this->template->assign_block_vars('NewsBlock', array(
				'CONTENT' 		=> $nrow['content'],
				'CHANNEL' 		=> $nrow['channel_name'],
				'TOPIC' 		=> $nrow['topic_name'],
				'EDITNEWS' 		=> build_url("action")."&amp;action=edit_news&amp;news_id=".$nrow['news_id'],
			));
		}
		
		// Add link to add a news
		$this->template->assign_var('ADDNEWS',build_url("action")."&amp;action=add_news");
	}
	
	/**
	* Add a channel
	*
	* @return null
	* @access public
	*/
	public function channel_add()
	{
		// Create a form key for preventing CSRF attacks
		add_form_key('consim_channel_add');
		// Create an array to collect errors that will be output to the user
		$errors = array();

		//get the consimUser Entity
		/** @var  \consim\core\entity\NewsChannel */
		$news_channel = $this->container->get('consim.core.entity.news_channel');

		// Is the form being submitted to us?
		if ($this->request->is_set_post('addChannel'))
		{
			// Test if the submitted form is valid
			if (!check_form_key('consim_channel_add'))
			{
				$errors[] = $this->user->lang('FORM_INVALID');
			}
			
			// Check if all required fields are set
			$this->check_data($errors,array('group','name','background','border'));

			// If no errors, process the form data
			if (empty($errors))
			{
				$news_channel->setGroupId($this->request->variable('group',2))->
					setChannelName($this->request->variable('name','',true))->
					setvRefresh($this->request->variable('vrefresh',0))->
					setBackground($this->request->variable('background','',true))->
					setBorder($this->request->variable('border','',true))->insert();
				redirect(build_url(array("action")));
			}
		}
		
		// Get all user groups from the database
		$sql = $this->db->sql_build_query("SELECT",array(
			'SELECT' 			=> 'g.group_id, g.group_name', 
			'FROM' 				=> array(GROUPS_TABLE => 'g',), 
			'WHERE' 			=> 'group_id <> 2 ORDER BY group_id'));
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('allUsers', array(
				'GROUPID' 		=> $row['group_id'],
				'GROUPNAME' 	=> (isset($this->user->lang['G_'.$row['group_name']])) ? $this->user->lang['G_'.$row['group_name']] : $row['group_name'],
			));
		}

		// Add the error messages to the template
		$this->template->assign_vars(array(
			'S_ERROR'	=> (sizeof($errors)) ? true : false,
			'ERROR_MSG'	=> (sizeof($errors)) ? implode('<br />', $errors) : '',
		));
	}
	
	/**
	* Edit a channel
	*
	* @param int $id The id of the news to be edited
	* @return null
	* @access public
	*/
	public function channel_edit($id)
	{
		// Create a form key for preventing CSRF attacks
		add_form_key('consim_channel_edit');
		// Create an array to collect errors that will be output to the user
		$errors = array();

		//get the consimUser Entity
		$news_channel = $this->container->get('consim.core.entity.news_channel');

		// Is the form being submitted to us?
		if ($this->request->is_set_post('addChannel'))
		{
			// Test if the submitted form is valid
			if (!check_form_key('consim_channel_edit'))
			{
				$errors[] = $this->user->lang('FORM_INVALID');
			}
			
			// Check if all required fields are set
			$this->check_data($errors,array('group','name','background','border'));

			// If no errors, process the form data
			if (empty($errors))
			{
				$news_channel->setId($id)->setGroupId($this->request->variable('group',2))->
					setChannelName($this->request->variable('name','',true))->
					setvRefresh($this->request->variable('vrefresh',0))->
					setBackground($this->request->variable('background','',true))->
					setBorder($this->request->variable('border','',true))->save();
				redirect(build_url(array("action","channel_id")));
			}
		}
		
		// Get all user groups from the database
		$sql = $this->db->sql_build_query("SELECT",array(
			'SELECT' 			=> 'g.group_id, g.group_name', 
			'FROM' 				=> array(GROUPS_TABLE => 'g',), 
			'WHERE' 			=> 'group_id <> 2 ORDER BY group_id'));
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('allUsers', array(
				'GROUPID' 		=> $row['group_id'],
				'GROUPNAME'		=> (isset($this->user->lang['G_'.$row['group_name']])) ? $this->user->lang['G_'.$row['group_name']] : $row['group_name'],
			));
		}
		
		// Get the current channel data from the database
		$sql = $this->db->sql_build_query("SELECT",array(
			'SELECT' 			=> 'group_id, channel_name, vRefresh, background_color, color', 
			'FROM' 				=> array('phpbb_consim_news_channel' => 'c' ), 
			'WHERE' 			=> 'channel_id ='. $id));
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		
		$this->template->assign_vars(array(
			'NAME' 				=> $row['channel_name'],
			'VREF_YES' 			=> ($row['vRefresh']==1) ? true : false,
			'VREF_NO' 			=> ($row['vRefresh']==0) ? true : false,
			'GID'				=> $row['group_id'],
			'BACKGROUND'		=> $row['background_color'],
			'COLOR'			=> $row['color'],
			'DELETE'			=> build_url("action").'&action=delete_channel&channel_id='.$id,
			'S_DELETE'			=> true,
			'S_ERROR'	=> (sizeof($errors)) ? true : false,
			'ERROR_MSG'	=> (sizeof($errors)) ? implode('<br />', $errors) : '',
		));
	}
	
	/**
	* Delete a channel
	*
	* @param int $id The id of the news to be deleted
	* @return null
	* @access public
	*/
	public function channel_delete($id)
	{
		if(confirm_box(true))
		{
			$this->container->get('consim.core.entity.news_channel')->setId($id)->delete();
		}
		else
		{
			// Request confirmation from the user to delete the topic
			confirm_box(false, $this->user->lang('CONFIRM_DELETE_CHANNEL'), build_hidden_fields(array(
				'mode' 			=> 'news',
				'action' 		=> 'delete_channel',
				'channel_id'	=> $id,
			)));
		}
		redirect(build_url(array("action","channel_id")));
	}
	
	/**
	* Add a news
	*
	* @return null
	* @access public
	*/
	public function news_add()
	{
		// Create a form key for preventing CSRF attacks
		add_form_key('consim_news_add');
		// Create an array to collect errors that will be output to the user
		$errors = array();

		//get the consimUser Entity
		$news = $this->container->get('consim.core.entity.news');

		// Is the form being submitted to us?
		if ($this->request->is_set_post('addNews'))
		{
			// Test if the submitted form is valid
			if (!check_form_key('consim_news_add'))
			{
				$errors[] = $this->user->lang('FORM_INVALID');
			}

			// Check if all required fields are set
			$this->check_data($errors,array('channel','topic','content'));

			// If no errors, process the form data
			if (empty($errors))
			{
				$news->setChannelId($this->request->variable('channel',0))->
					setTopicId($this->request->variable('topic',0))->
					setContent($this->request->variable('content','',true))->insert();
				redirect(build_url(array("action","news_id")));
			}
		}
		
		// Get all channels from the database
		$sql = $this->db->sql_build_query("SELECT",array(
			'SELECT' 			=> 'channel_id, channel_name', 
			'FROM' 				=> array('phpbb_consim_news_channel' => 'c',)));
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('allChannels', array(
				'CHANNELID'	 	=> $row['channel_id'],
				'NAME' 			=> $row['channel_name'],
			));
		}
		
		// Get all topics from the database
		$sql = $this->db->sql_build_query("SELECT",array(
			'SELECT' 			=> 'topic_id, topic_name', 
			'FROM' 				=> array('phpbb_consim_news_topics' => 't',)));
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('allTopics', array(
				'TOPICID'	 	=> $row['topic_id'],
				'NAME' 			=> $row['topic_name'],
			));
		}

		$this->template->assign_vars(array(
			'S_ERROR'	=> (sizeof($errors)) ? true : false,
			'ERROR_MSG'	=> (sizeof($errors)) ? implode('<br />', $errors) : '',
		));
	}
	
	/**
	* Edit a news
	*
	* @param int $id The id of the news to be edited
	* @return null
	* @access public
	*/
	public function news_edit($id)
	{
		// Create a form key for preventing CSRF attacks
		add_form_key('consim_news_edit');
		// Create an array to collect errors that will be output to the user
		$errors = array();

		//get the consimUser Entity
		$news = $this->container->get('consim.core.entity.news');

		// Is the form being submitted to us?
		if ($this->request->is_set_post('addNews'))
		{
			// Test if the submitted form is valid
			if (!check_form_key('consim_news_edit'))
			{
				$errors[] = $this->user->lang('FORM_INVALID');
			}
			
			// Check if all required fields are set
			$this->check_data($errors,array('channel','topic','content'));
			
			// If no errors, process the form data
			if (empty($errors))
			{
				$news->setId($id)->setChannelId($this->request->variable('channel',0))->
					setTopicId($this->request->variable('topic',0))->
					setContent($this->request->variable('content','',true))->save();
				redirect(build_url(array("action","news_id")));
			}
		}
		
		// Get all channels from the database
		$sql = $this->db->sql_build_query("SELECT",array(
			'SELECT' 			=> 'channel_id, channel_name', 
			'FROM' 				=> array('phpbb_consim_news_channel' => 'c',)));
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('allChannels', array(
				'CHANNELID'	 	=> $row['channel_id'],
				'NAME' 			=> $row['channel_name'],
			));
		}
		
		// Get all topics from the database
		$sql = $this->db->sql_build_query("SELECT",array(
			'SELECT' 			=> 'topic_id, topic_name', 
			'FROM' 				=> array('phpbb_consim_news_topics' => 't',)));
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('allTopics', array(
				'TOPICID'	 	=> $row['topic_id'],
				'NAME' 			=> $row['topic_name'],
			));
		}
		
		// Get the current news data from the database
		$sql = $this->db->sql_build_query("SELECT",array(
			'SELECT' 			=> 'channel_id, topic_id, content',
			'FROM' 				=> array('phpbb_consim_news' => 'n' ), 
			'WHERE' 			=> 'news_id ='. $id));
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		
		$this->template->assign_vars(array(
			'CONTENT' 			=> $row['content'],
			'CID'				=> $row['channel_id'],
			'TID'				=> $row['topic_id'],
			'DELETE'			=> build_url("action").'&action=delete_news&news_id='.$id,
			'S_DELETE'			=> true,
			'S_ERROR'	=> (sizeof($errors)) ? true : false,
			'ERROR_MSG'	=> (sizeof($errors)) ? implode('<br />', $errors) : '',
		));
	}
	
	/**
	* Delete a news
	*
	* @param int $id The id of the news to be deleted
	* @return null
	* @access public
	*/
	public function news_delete($id)
	{
		if(confirm_box(true))
		{
			$this->container->get('consim.core.entity.news')->setId($id)->delete();
		}
		else
		{
			// Request confirmation from the user to delete the topic
			confirm_box(false, $this->user->lang('CONFIRM_DELETE_NEWS'), build_hidden_fields(array(
				'mode' 			=> 'news',
				'action' 		=> 'delete_news',
				'news_id' 		=> $id,
			)));
		}
		redirect(build_url(array("action","news_id")));
	}
	
	/**
	* Add a topic
	*
	* @return null
	* @access public
	*/
	public function topic_add()
	{

		// Check if all required fields are set
		$this->check_data($errors,array('topicTitle'));

		// If no errors, process the form data
		if (empty($errors))
		{
			$this->container->get('consim.core.entity.news_topics')->setTopicName($this->request->variable('topicTitle','',true))->insert();
		}

		$this->template->assign_vars(array(
			'S_ERROR'	=> (sizeof($errors)) ? true : false,
			'ERROR_MSG'	=> (sizeof($errors)) ? implode('<br />', $errors) : '',
		));
	}
	
	/**
	* Edit a topic
	*
	* @param int $id The id of the news to be edited
	* @return null
	* @access public
	*/
	public function topic_edit($id)
	{
		// Check if all required fields are set
		$this->check_data($errors,array('title'));

		// If no errors, process the form data
		if (empty($errors))
		{
			$this->container->get('consim.core.entity.news_topics')->setTopicName($this->request->variable('title','',true))->setId($id)->save();
		}

		$this->template->assign_vars(array(
			'S_ERROR'	=> (sizeof($errors)) ? true : false,
			'ERROR_MSG'	=> (sizeof($errors)) ? implode('<br />', $errors) : '',
		));
	}
	
	/**
	* Delete a topic
	*
	* @param int $id The id of the news to be deleted
	* @return null
	* @access public
	*/
	public function topic_delete($id)
	{
		if(confirm_box(true))
		{
			$this->container->get('consim.core.entity.news_topics')->setId($id)->delete();
			redirect(build_url(array("action","topic_id")));
		}
		else
		{
			// Request confirmation from the user to delete the topic
			confirm_box(false, $this->user->lang('CONFIRM_DELETE_TOPIC'), build_hidden_fields(array(
				'mode' 			=> 'news',
				'action' 		=> 'delete_topic',
				'topic_id' 		=> $id,
			)));
		}
	}
}
