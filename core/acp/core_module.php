<?php

/**
*
* @package ConSim for phpBB3.1
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @copyright (c) 2016 Jonas Heitmann (kommodoree@googlemail.com)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace consim\core\acp;

class core_module
{
	public $u_action;

	public function main($id, $mode)
	{
		/**
		 * @var \Symfony\Component\DependencyInjection\ContainerInterface $phpbb_container
		 * @var \phpbb\request\request $request
		 * @var \phpbb\user $user
		 * @var \phpbb\template\template $template
		 */
		global $phpbb_container, $request, $user, $template;

		// Requests
		$action = $request->variable('action', '');
		
		// Load the module modes
		$this->page_title = $user->lang['CONSIM_TITLE'];

		switch ($mode)
		{
			case 'anniversary':
				
				// Get an instance of the admin controller and set the template (we only need acp_rsp_user in this file)
				$controller = $phpbb_container->get('consim.core.controller.acpanniversary');
				$this->tpl_name = 'consim_anniversary';
				$user->add_lang_ext('/consim/core', 'consim_anniversary'); // Load the needed language file

				switch($action)
				{
					case "add_anniversary":
						$controller->anniversary_add();
						break;
					
					case "delete_anniversary":
						$controller->anniversary_delete($request->variable('anniversary_id',0));
						break;
					
					default:
						$template->assign_var('S_OVERVIEW',true); // Only show the needed part of the template file
						
						// Update or add anniversaries
						if($request->is_set_post('addAnniversary'))
						{
							$controller->anniversary_add();
						}
						elseif($request->is_set_post('updateAnniversary'))
						{
							$controller->anniversary_update($request->variable('anniversary_id',0));
						}
	
						$controller->overview();
						break;
				}
				break;

			case 'news':
			default:

				// Get an instance of the admin controller and set the template (we only need acp_rsp_user in this file)
				$news_controller = $phpbb_container->get('consim.core.controller.acpnews');
				$this->tpl_name = 'consim_news';
				$user->add_lang_ext('/consim/core', 'consim_news'); // Load the needed language file

				switch($action)
				{
					case "add_channel":
						$template->assign_var('S_ADD_CHANNEL',true); // Only show the needed part of the template file
						$news_controller->channel_add();
						break;
					
					case "add_news":
					$template->assign_var('S_ADD_NEWS',true); // Only show the needed part of the template file
						$news_controller->news_add();
						break;
					
					case "edit_channel":
					$template->assign_var('S_EDIT_CHANNEL',true); // Only show the needed part of the template file
						$news_controller->channel_edit($request->variable('channel_id',0));
						break;
						
					case "edit_news":
						$template->assign_var('S_EDIT_NEWS',true); // Only show the needed part of the template file
						$news_controller->news_edit($request->variable('news_id',0));
						break;
					
					case "delete_channel":
						$news_controller->channel_delete($request->variable('channel_id',0));
						break;		
						
					case "delete_news":
						$news_controller->news_delete($request->variable('news_id',0));
						break;
						
					case "delete_topic":
						$news_controller->topic_delete($request->variable('topic_id',0));
						break;
						
					default:
						$template->assign_var('S_OVERVIEW',true); // Only show the needed part of the template file
						if($request->is_set_post('addTopic'))
						{
							$news_controller->topic_add();
						}
						elseif($request->is_set_post('updateTopic'))
						{
							$news_controller->topic_edit($request->variable('topic_id',0));
						}	
						$news_controller->news_overview();
						break;
				}
				
				// Return to stop execution of this script 
				return;
			break;
		}
	}
}
