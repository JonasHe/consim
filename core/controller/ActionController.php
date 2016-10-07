<?php
/**
*
* @package ConSim for phpBB3.1
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace consim\core\controller;

/**
* Main controller
*/
class ActionController extends AbstractController
{
	/** @var \consim\core\service\BuildingService */
	protected $buildingService;

	/** @var \consim\core\service\RouteService */
	protected $routeService;

	/** @var \consim\core\service\WorkService */
	protected $workService;

	/**
	 * Constructor
	 *
	 * @param \phpbb\controller\helper				$helper				Controller helper object
	 * @param \phpbb\user							$user				User object
	 * @param \phpbb\template\template				$template			Template object
	 * @param \phpbb\request\request				$request			Request object
	 * @param \consim\core\service\ActionService	$actionService		ActionService object
	 * @param \consim\core\service\BuildingService	$buildingService	BuildingService object
	 * @param \consim\core\service\InventoryService	$inventoryService	InventoryService object
	 * @param \consim\core\service\LocationService	$locationService	LocationService object
	 * @param \consim\core\service\RouteService		$routeService		RouteService object
	 * @param \consim\core\service\UserService		$userService		UserService object
	 * @param \consim\core\service\UserSkillService	$userSkillService	UserSkillService object
	 * @param \consim\core\service\WeatherService	$weatherService		WeatherService object
	 * @param \consim\core\service\WidgetService	$widgetService		WidgetService object
	 * @param \consim\core\service\WorkService		$workService		WorkService object
	 * @return \consim\core\controller\ActionController
	 * @access public
	 */
	public function __construct(\phpbb\controller\helper $helper,
		\phpbb\user $user,
		\phpbb\template\template $template,
		\phpbb\request\request $request,
		\consim\core\service\ActionService $actionService,
		\consim\core\service\BuildingService $buildingService,
		\consim\core\service\InventoryService $inventoryService,
		\consim\core\service\LocationService $locationService,
		\consim\core\service\RouteService $routeService,
		\consim\core\service\UserService $userService,
		\consim\core\service\UserSkillService $userSkillService,
		\consim\core\service\WeatherService $weatherService,
		\consim\core\service\WidgetService $widgetService,
		\consim\core\service\WorkService $workService)
	{
		$this->helper = $helper;
		$this->user = $user;
		$this->template = $template;
		$this->request = $request;
		$this->actionService = $actionService;
		$this->buildingService = $buildingService;
		$this->inventoryService = $inventoryService;
		$this->locationService = $locationService;
		$this->routeService = $routeService;
		$this->userService = $userService;
		$this->userSkillService = $userSkillService;
		$this->weatherService = $weatherService;
		$this->widgetService = $widgetService;
		$this->workService = $workService;

		$this->init();
		return $this;
	}

	/**
	 * Display current action
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function showActivity()
	{
		$consim_user = $this->userService->getCurrentUser();
		if($consim_user->getActive())
		{
			//get current action
			$action = $this->actionService->getCurrentAction();

			if($action->getRouteId() > 0)
			{
				return $this->showTravel($action->getId());
			}
			// is user working?
			if($action->getWorkId() > 0)
			{
				return $this->showWork($action->getId());
			}
		}
		redirect($this->helper->route('consim_core_index'));
		return null;
	}

	/**
	 * Display work action
	 *
	 * @param int	$action_id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function showWork($action_id)
	{
		$action = $this->actionService->getAction($action_id);

		//check if the user is owner of action
		if($action->getUserId() != $this->userService->getCurrentUser()->getUserId() || $action->getWorkId() == 0)
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		$now = time();
		$time = $action->getEndTime() - $now;

		$work = $this->workService->getWork($action->getWorkId());
		$location = $this->locationService->getLocation($action->getLocationId());
		$building = $this->buildingService->findBuilding($location->getId(), $work->getBuildingTypeId());

		//User must finished the work
		if($action->getStatus() == 2)
		{
			$s_hidden_fields = build_hidden_fields(array(
				'action_id'		=> $action->getId(),
			));
			$this->template->assign_vars(array(
				'IS_CONFIRM_FINISH'	=> TRUE,
				'U_FINISH'			=> ($action->getStatus() == 2)? $this->helper->route('consim_core_work_end') : FALSE,
				'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
			));
			add_form_key('working_end');
		}

		//Work finished
		if($action->getStatus() == 1)
		{
			$result = $action->getResult();
			$this->template->assign_vars(array(
				'IS_WORK_FINISHED'			=> TRUE,
				'WORK_RESULT_ALL'			=> $result['conditions']['all'],
				'WORK_RESULT_1'				=> (isset($result['conditions'][0]))? $result['conditions'][0] : 0,
				'WORK_RESULT_2'				=> (isset($result['conditions'][1]))? $result['conditions'][1] : 0,
				'WORK_RESULT_3'				=> (isset($result['conditions'][2]))? $result['conditions'][2] : 0,
				'WORK_EXPERIENCE'			=> $result['experience'],
			));
		}

		$this->workService->allWorkOutputsToTemplate($work->getId());

		// Set output vars for display in the template
		$this->template->assign_vars(array(
			'SHOW_WORKING'				=> TRUE,
			'IS_WORKING'				=> ($action->getStatus() == 0)? TRUE : FALSE,
			'WORK_NAME'					=> $work->getName(),
			'WORK_CONDITION_1_TYPE'		=> $work->getCondition1Name(),
			'WORK_CONDITION_1_TRIALS'	=> $work->getCondition1Trials(),
			'WORK_CONDITION_1_VALUE'	=> $work->getCondition1Value(),
			'WORK_CONDITION_2_TYPE'		=> $work->getCondition2Name(),
			'WORK_CONDITION_2_TRIALS'	=> $work->getCondition2Trials(),
			'WORK_CONDITION_2_VALUE'	=> $work->getCondition2Value(),
			'WORK_CONDITION_3_TYPE'		=> $work->getCondition3Name(),
			'WORK_CONDITION_3_TRIALS'	=> $work->getCondition3Trials(),
			'WORK_CONDITION_3_VALUE'	=> $work->getCondition3Value(),
			'WORK_CONDITION_ALL'		=> ($work->getCondition1Trials() + $work->getCondition2Trials() + $work->getCondition3Trials()),
			'WORK_EXPERIENCE_POINTS'	=> implode("/", $work->getExperiencePoints()),
			'WORK_BUILDING_NAME'		=> ($building->getName() != '')? '"' . $building->getName() . '"' : '',
			'WORK_BUILDING_TYPE'		=> $building->getTypeName(),
			'WORK_LOCATION_NAME'		=> $location->getName(),
			'WORK_TIME'					=> ($action->getStatus() == 0)? date("i:s", $time) : FALSE,
		));

		// Send all data to the template file
		return $this->helper->render('consim_working.html', $this->user->lang('CONSIM'));
	}

	/**
	 * Display travel action
	 *
	 * @param $action_id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function showTravel($action_id)
	{
		$action = $this->actionService->getAction($action_id);

		//check if the user is owner of action
		if($action->getUserId() != $this->userService->getCurrentUser()->getUserId() || $action->getRouteId() == 0)
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		$now = time();
		$time = $action->getEndTime() - $now;

		$route = $this->routeService->getRoute($action->getRouteId());
		$location = $this->locationService->getLocationFromRoute($action->getLocationId(), $route);

		// Set output vars for display in the template
		$this->template->assign_vars(array(
			'IS_TRAVELING'				=> TRUE,
			'START_LOCATION_NAME'       => $location['start']->getName(),
			'START_LOCATION_IMAGE'      => $location['start']->getImage(),
			'START_LOCATION_TYPE'       => $location['start']->getType(),
			'START_LOCATION_PROVINCE'   => $location['start']->getProvince(),
			'START_LOCATION_COUNTRY'    => $location['start']->getCountry(),
			'START_TIME'                => date("d.m.Y - H:i:s", $action->getStartTime()),
			'END_LOCATION_NAME'         => $location['end']->getName(),
			'END_LOCATION_IMAGE'        => $location['end']->getImage(),
			'END_LOCATION_TYPE'         => $location['end']->getType(),
			'END_LOCATION_PROVINCE'     => $location['end']->getProvince(),
			'END_LOCATION_COUNTRY'      => $location['end']->getCountry(),
			'END_TIME'                  => date("d.m.Y - H:i:s", $action->getEndTime()),
			'COUNTDOWN'                 => date("i:s", $time),
		));

		// Send all data to the template file
		return $this->helper->render('consim_traveling.html', $this->user->lang('CONSIM'));
	}
}

