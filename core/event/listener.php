<?php
/**
* @package ConSim for phpBB3.1
*
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @copyright (c) 2016 Jonas Heitmann (kommodoree@googlemail.com)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*/

namespace consim\core\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $controller_helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/**
	* Constructor
	*
	* @param \phpbb\config\config		$config				Config object
	* @param \phpbb\controller\helper	$controller_helper	Controller helper object
	* @param \phpbb\template\template	$template			Template object
	* @param \phpbb\user				$user				User object
	* @param \phpbb\db\driver\driver_interface	$db				Database object
	* @access public
	*/
	public function __construct(\phpbb\config\config $config,
								\phpbb\controller\helper $controller_helper,
								\phpbb\template\template $template,
								\phpbb\user $user,
								\phpbb\db\driver\driver_interface $db)
	{
		$this->config = $config;
		$this->controller_helper = $controller_helper;
		$this->template = $template;
		$this->user = $user;
		$this->db = $db;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'		=> 'load_language_on_setup',
			'core.page_header'		=> 'add_page_header_link',
		);
	}

	/**
	* Load common consim language files during user setup and add the newsbar and history hint
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'consim/core',
			'lang_set' => 'consim_common',
		);
		$event['lang_set_ext'] = $lang_set_ext;

		// Add the Newsbar
		$groups = $channel = $anniversary = array();
		
		//Catch all groups from the database where the user is a member of
		$sql = 'SELECT group_id FROM '. USER_GROUP_TABLE .' WHERE user_id = ' . $this->user->data['user_id'];
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrowset($result);
		foreach($row as $value){
			$groups[] = $value['group_id'];
		}
		
		// Catch the first channel from the database where the user is a member of the group
		$csql = 'SELECT * FROM phpbb_consim_news_channel';
		$cresult = $this->db->sql_query($csql);
		while($crow = $this->db->sql_fetchrow($cresult))
		{
			if(in_array($crow['group_id'],$groups))
			{
				$channel['id'] 			= $crow['channel_id'];
				$channel['name'] 		= $crow['channel_name'];
				$channel['vRefresh'] 	= $crow['vRefresh'];
				$channel['background'] 	= $crow['background_color'];
				$channel['color'] 		= $crow['color'];
				break;
			}
		}
		
		if(isset($channel['id']) && $channel['id']!="") // If the user is allowed to see a channel proceed with fetching all topics and news
		{
			// Catch all news from the database
			$nsql = "SELECT n.news_id, n.content, t.topic_name
				FROM phpbb_consim_news n 
				INNER JOIN phpbb_consim_news_topics t
				ON t.topic_id = n.topic_id
				WHERE n.channel_id = ".$channel['id']." 
				ORDER BY n.topic_id";
			$nresult = $this->db->sql_query($nsql);
			while($nrow = $this->db->sql_fetchrow($nresult))
			{
				$this->template->assign_block_vars('allNews', array(
				'TOPIC'		=> $nrow['topic_name'],
				'CONTENT' 	=> $nrow['content'],
				));
			}
		}
		
		// Add the history hint
		$anniversary['today'] = $anniversary['tomorrow'] = "";

		$sql = "SELECT event, day, year, link
			FROM phpbb_consim_anniversary
			WHERE month = ".date('m');
		$result = $this->db->sql_query($sql);	
		while($row = $this->db->sql_fetchrow($result))
		{
			$event = (($row['year']!=0) ? date("Y")-(int)$row['year'].". ": "").$row['event'];
			if($row['day']==date("j"))
			{
				if($anniversary['today']!="")
				{
					$anniversary['today'] .= ($row['link']!="http://") ? '<a href="'.$row['link'].'">'.$event.'</a>' : $event;
				}
				else
				{
					$anniversary['today'] = ($row['link']!="http://") ? '<a href="'.$row['link'].'">'.$event.'</a>' : $event;
				}
			}
			if($row['day']==(new \DateTime('tomorrow'))->format('j'))
			{
				if($anniversary['tomorrow']!="")
				{
					$anniversary['today'] .= ($row['link']!="http://") ? '<a href="'.$row['link'].'">'.$event.'</a>' : $event;
				}
				else
				{
					$anniversary['tomorrow'] = ($row['link']!="http://") ? '<a href="'.$row['link'].'">'.$event.'</a>' : $event;
				}
			}
		}

		// Pass the data to the template
		$this->template->assign_vars(array(
			'S_NEWSTICKER'					=> (isset($channel['id'])) ? true : false,
			'CHANNEL'						=> (isset($channel['name'])) ? $channel['name'].' '.date('H:i') : "",
			'VREFRESH'						=> (isset($channel['vRefresh'])) ? $channel['vRefresh'] : 0,
			'CHANNEL_BACKGROUND'			=> (isset($channel['background'])) ? $channel['background'] : "",
			'CHANNEL_COLOR'					=> (isset($channel['color'])) ? $channel['color'] : "",
			'ANNIVERSARY_TODAY'				=> $anniversary['today'],
			'ANNIVERSARY_TOMORROW'			=> $anniversary['tomorrow'],
		));
	}

	/**
	* Create a URL to the consim controller file for the header linklist
	*
	* @return null
	* @access public
	*/
	public function add_page_header_link()
	{
		$this->template->assign_vars(array(
			'U_CONSIM' => $this->controller_helper->route('consim_core_index'),
		));
	}
}
