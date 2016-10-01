<?php
/**
 *
 * @package ConSim for phpBB3.1
 * @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace consim\core\controller;

use consim\core\entity\UserSkill;
use consim\core\entity\WorkOutput;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Main controller
 */
abstract class AbstractController
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var ContainerInterface */
	protected $container;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var  \consim\core\service\WidgetService */
	protected $widgetService;

	/**
	 * Class-Variables
	 **/
	/** @var  \consim\core\entity\ConsimUser */
	protected $consim_user;
	/** @var  \consim\core\entity\Location */
	protected $consim_user_location;
	/** @var  UserSkill[] */
	protected $consim_user_skills;

	/**
	 * Initiated all important variable
	 * and check if it a consim-user
	 *
	 * @access protected
	 */
	protected function init()
	{
		//Check if user in consim
		if($this->user->data['consim_register'] == 0)
		{
			//go to register page
			redirect($this->helper->route('consim_core_register'));
			return null;
		}

		// Add language file
		$this->user->add_lang_ext('consim/core', 'consim_common');
		$this->add_navlinks();

		//Check all finished Actions
		$actionService = $this->container->get('consim.core.service.action');
		$actionService->finishedActions();

		//get Widget
		//Todo: to consturctor
		$this->widgetService = $this->container->get('consim.core.service.widget');

		//Get the ConSim-User
		$this->consim_user = $this->container->get('consim.core.entity.consim_user')->load($this->user->data['user_id']);

		// get User Skill and add to template
		$userSkillService = $this->container->get('consim.core.service.user_skill');
		$this->consim_user_skills = $userSkillService->getUserSkills($this->user->data['user_id']);
		//User Skill to Template
		$this->widgetService->userSkillWidget($userSkillService->sortSkillsByCategory($this->consim_user_skills));

		// Get inventory and add to template
		$inventory = $this->container->get('consim.core.service.inventory')->getInventory($this->consim_user->getUserId());
		$this->widgetService->inventoryWidget($inventory);

		//Get User-Location
		$this->consim_user_location = $this->container->get('consim.core.entity.location')->load($this->consim_user->getLocationId());

		//Is User active?
		if($this->consim_user->getActive()) {
			//get current action
			$action = $this->container->get('consim.core.service.action')->getCurrentActionFromUser(
				$this->user->data['user_id']
			);
			//Is User traveling?
			if ($action->getRouteId() > 0) {
				$this->widgetService->travelWidget($action);
			}
			// is user working?
			if ($action->getWorkId() > 0) {
				$this->widgetService->workWidget($action);
			}
		}

		//Experience to Template
		$this->widgetService->experienceWidget($this->consim_user);
		//Location to Template
		$this->widgetService->locationWidget($this->consim_user_location);

		// Set output vars for display in the template
		$this->template->assign_vars(array(
			//Informations for current location and time
			'TIME'							=> date("d.m.Y - H:i:s", time()),
			'GO_TO_INFORMATION'				=> $this->helper->route('consim_core_activity'),
			'U_OVERVIEW'					=> $this->helper->route('consim_core_index'),
		));
	}

	/**
	 * Adding link at navlinks
	 *
	 * @param string $name
	 * @param string $route
	 * @access protected
	 */
	protected function add_navlinks($name = '', $route = '')
	{
		if(empty($name))
		{
			$this->template->assign_block_vars('navlinks', array(
				'FORUM_NAME'		=> $this->user->lang('CONSIM'),
				'U_VIEW_FORUM'		=> $this->helper->route('consim_core_index'),
			));
		}
		else
		{
			$this->template->assign_block_vars('navlinks', array(
				'FORUM_NAME'		=> $name,
				'U_VIEW_FORUM'		=> $route,
			));
		}
	}

	protected function is_valid($value)
	{
		return !empty($value) && preg_match('/^\w+$/', $value);
	}
}

