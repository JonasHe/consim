<?php
/**
*
* @package ConSim for phpBB3.1
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace consim\core\controller;

use consim\core\entity\Action;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Main controller
*/
class ActionController extends AbstractController
{
	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config				$config				Config object
	 * @param \phpbb\controller\helper			$helper				Controller helper object
	 * @param ContainerInterface					$container			Service container interface
	 * @param \phpbb\user						$user				User object
	 * @param \phpbb\template\template			$template			Template object
	 * @param \phpbb\request\request				$request			Request object
	 * @param \consim\core\service\ActionService	$actionService		ActionService object
	 * @param \consim\core\service\InventoryService	$inventoryService	InventoryService object
	 * @param \consim\core\service\LocationService	$locationService	LocationService object
	 * @param \consim\core\service\UserService		$userService		UserService object
	 * @param \consim\core\service\UserSkillService	$userSkillService	UserSkillService object
	 * @param \consim\core\service\WeatherService	$weatherService		WeatherService object
	 * @param \consim\core\service\widgetService	$widgetService		WidgetService object
	 * @return \consim\core\controller\ActionController
	 * @access public
	 */
	public function __construct(\phpbb\config\config $config,
		ContainerInterface $container,
		\phpbb\controller\helper $helper,
		\phpbb\user $user,
		\phpbb\template\template $template,
		\phpbb\request\request $request,
		\consim\core\service\ActionService $actionService,
		\consim\core\service\InventoryService $inventoryService,
		\consim\core\service\LocationService $locationService,
		\consim\core\service\UserService $userService,
		\consim\core\service\UserSkillService $userSkillService,
		\consim\core\service\WeatherService $weatherService,
		\consim\core\service\WidgetService $widgetService)
	{
		$this->config = $config;
		$this->container = $container;
		$this->helper = $helper;
		$this->user = $user;
		$this->template = $template;
		$this->request = $request;
		$this->actionService = $actionService;
		$this->inventoryService = $inventoryService;
		$this->locationService = $locationService;
		$this->userService = $userService;
		$this->userSkillService = $userSkillService;
		$this->weatherService = $weatherService;
		$this->widgetService = $widgetService;

		$this->init();
		return $this;
	}

	public function showActivity()
	{
		$consim_user = $this->userService->getCurrentUser();
		if($consim_user->getActive())
		{
			//get current action
			$action = $this->actionService->getCurrentAction();

			if($action->getRouteId() > 0)
			{
				return $this->showTravel($action->getId(), $action);
			}
			// is user working?
			if($action->getWorkId() > 0)
			{
				return $this->showWork($action->getId(), $action);
			}
		}
		redirect($this->helper->route('consim_core_index'));
		return null;
	}

	public function showWork($work_id, $work = null)
	{
		if($work === null)
		{
			$work = $this->container->get('consim.core.entity.action')->load($work_id);
		}

		//check if the user is owner of action
		if($work->getUserId() != $this->userService->getCurrentUser()->getUserId() || $work->getWorkId() == 0)
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		$now = time();
		$time = $work->getEndTime() - $now;

		$working = $this->container->get('consim.core.entity.work')->load($work->getWorkId());
		$location = $this->container->get('consim.core.entity.location')->load($work->getLocationId());
		$building = $this->container->get('consim.core.entity.building')->find($location->getId(), $working->getBuildingTypeId());

		//User must finished the work
		if($work->getStatus() == 2)
		{
			$s_hidden_fields = build_hidden_fields(array(
				'action_id'		=> $work->getId(),
			));
			$this->template->assign_vars(array(
				'IS_CONFIRM_FINISH'	=> TRUE,
				'U_FINISH'			=> ($work->getStatus() == 2)? $this->helper->route('consim_core_work_end') : FALSE,
				'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
			));
			add_form_key('working_end');
		}

		$result = array();
		//Work finished
		if($work->getStatus() == 1)
		{
			$result = $work->getResult();
			$this->template->assign_vars(array(
				'IS_WORK_FINISHED'			=> TRUE,
				'WORK_RESULT_ALL'			=> $result['conditions']['all'],
				'WORK_RESULT_1'				=> (isset($result['conditions'][0]))? $result['conditions'][0] : 0,
				'WORK_RESULT_2'				=> (isset($result['conditions'][1]))? $result['conditions'][1] : 0,
				'WORK_RESULT_3'				=> (isset($result['conditions'][2]))? $result['conditions'][2] : 0,
				'WORK_EXPERIENCE'			=> $result['experience'],
			));
		}

		//set output to template
		foreach ($working->getSortedOutputs() as $type => $outputs)
		{
			$this->template->assign_block_vars('work_outputs', array(
				'TYPE'			=> $outputs['name'],
				'VALUE'			=> (isset($result) && !empty($result['outputs'][$type]))? $result['outputs'][$type] : 0,
			));

			/** @var \consim\core\entity\WorkOutput[] $outputs */
			for($i=0; $i < 5; $i++)
			{
				$this->template->assign_block_vars('work_outputs.types', array(
					'VALUE'			=> (isset($outputs[$i]))? $outputs[$i]->getOutputValue() : 0,
				));
			}
		}

		// Set output vars for display in the template
		$this->template->assign_vars(array(
			'SHOW_WORKING'				=> TRUE,
			'IS_WORKING'				=> ($work->getStatus() == 0)? TRUE : FALSE,
			'WORK_NAME'					=> $working->getName(),
			'WORK_CONDITION_1_TYPE'		=> $working->getCondition1Name(),
			'WORK_CONDITION_1_TRIALS'	=> $working->getCondition1Trials(),
			'WORK_CONDITION_1_VALUE'	=> $working->getCondition1Value(),
			'WORK_CONDITION_2_TYPE'		=> $working->getCondition2Name(),
			'WORK_CONDITION_2_TRIALS'	=> $working->getCondition2Trials(),
			'WORK_CONDITION_2_VALUE'	=> $working->getCondition2Value(),
			'WORK_CONDITION_3_TYPE'		=> $working->getCondition3Name(),
			'WORK_CONDITION_3_TRIALS'	=> $working->getCondition3Trials(),
			'WORK_CONDITION_3_VALUE'	=> $working->getCondition3Value(),
			'WORK_CONDITION_ALL'		=> ($working->getCondition1Trials() + $working->getCondition2Trials() + $working->getCondition3Trials()),
			'WORK_EXPERIENCE_POINTS'	=> implode("/", $working->getExperiencePoints()),
			'WORK_BUILDING_NAME'		=> ($building->getName() != '')? '"' . $building->getName() . '"' : '',
			'WORK_BUILDING_TYPE'		=> $building->getTypeName(),
			'WORK_LOCATION_NAME'		=> $location->getName(),
			'WORK_TIME'					=> ($work->getStatus() == 0)? date("i:s", $time) : FALSE,
		));

		// Send all data to the template file
		return $this->helper->render('consim_working.html', $this->user->lang('CONSIM'));
	}

	public function showTravel($travel_id, $travel = null)
	{
		if($travel === null)
		{
			$travel = $this->container->get('consim.core.entity.action')->load($travel_id);
		}

		//check if the user is owner of action
		if($travel->getUserId() != $this->userService->getCurrentUser()->getUserId() || $travel->getRouteId() == 0)
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		$now = time();
		$time = $travel->getEndTime() - $now;

		$route = $this->container->get('consim.core.entity.route')->load($travel->getRouteId());
		$start_location = $this->container->get('consim.core.entity.location')->load($travel->getLocationId());
		$end_location = $this->container->get('consim.core.entity.location');
		if($travel->getLocationId() == $route->getStartLocationId())
		{
			$end_location->load($route->getEndLocationId());
		}
		else
		{
			$end_location->load($route->getStartLocationId());
		}

		// Set output vars for display in the template
		$this->template->assign_vars(array(
			'IS_TRAVELING'				=> TRUE,
			'START_LOCATION_NAME'       => $start_location->getName(),
			'START_LOCATION_IMAGE'      => $start_location->getImage(),
			'START_LOCATION_TYPE'       => $start_location->getType(),
			'START_LOCATION_PROVINCE'   => $start_location->getProvince(),
			'START_LOCATION_COUNTRY'    => $start_location->getCountry(),
			'START_TIME'                => date("d.m.Y - H:i:s", $travel->getStartTime()),
			'END_LOCATION_NAME'         => $end_location->getName(),
			'END_LOCATION_IMAGE'        => $end_location->getImage(),
			'END_LOCATION_TYPE'         => $end_location->getType(),
			'END_LOCATION_PROVINCE'     => $end_location->getProvince(),
			'END_LOCATION_COUNTRY'      => $end_location->getCountry(),
			'END_TIME'                  => date("d.m.Y - H:i:s", $travel->getEndTime()),
			'COUNTDOWN'                 => date("i:s", $time),
		));

		// Send all data to the template file
		return $this->helper->render('consim_traveling.html', $this->user->lang('CONSIM'));
	}
}

