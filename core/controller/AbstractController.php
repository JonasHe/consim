<?php
/**
 *
 * @package ConSim for phpBB3.1
 * @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace consim\core\controller;

use consim\core\exception\base;
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

	/** @var  \consim\core\service\ActionService */
	protected $actionService;

	/** @var  \consim\core\service\InventoryService */
	protected $inventoryService;

	/** @var \consim\core\service\LocationService */
	protected $locationService;

	/** @var  \consim\core\service\UserService */
	protected $userService;

	/** @var  \consim\core\service\UserSkillService */
	protected $userSkillService;

	/** @var  \consim\core\service\WeatherService */
	protected $weatherService;

	/** @var  \consim\core\service\WidgetService */
	protected $widgetService;

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
		$this->actionService->finishedActions();

		//Get the ConSim-User
		$consim_user = $this->userService->getCurrentUser();

		// get User Skill and add to template
		$user_skills = $this->userSkillService->getCurrentUserSkills();
		//User Skill to Template
		$this->widgetService->userSkillWidget($this->userSkillService->sortSkillsByCategory($user_skills));

		// Get inventory and add to template
		$inventory = $this->inventoryService->getCurrentInventory();
		$this->widgetService->inventoryWidget($inventory);

		//Is User active?
		if($consim_user->getActive()) {
			//get current action
			$action = $this->actionService->getCurrentAction();
			//Is User traveling?
			if ($action->getRouteId() > 0) {
				$this->widgetService->travelWidget($action);
			}
			// is user working?
			if ($action->getWorkId() > 0) {
				$this->widgetService->workWidget($action);
			}
		}

		//Get User-Location
		$user_location = $this->locationService->getCurrentLocation();

		//Experience to Template
		$this->widgetService->experienceWidget($consim_user);
		//Location to Template
		$this->widgetService->locationWidget($user_location);

		//Set Weather to Template
		try {
			$this->weatherService
				->loadWeatherFromProvinceID($user_location->getProvinceID())
				->setWeatherWidget();
		}
		catch (base $e)
		{
			$this->template->assign_var('OWM_ERROR',$this->user->lang($e->getMessage()));
		}



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

