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
class RequestController
{
	/** @var ContainerInterface */
	protected $container;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var  \consim\core\service\ActionService */
	protected $actionService;

	/** @var \consim\core\service\RouteService */
	protected $routeService;

	/** @var  \consim\core\service\UserService */
	protected $userService;

	/** @var  \consim\core\service\UserSkillService */
	protected $userSkillService;

	/** @var \consim\core\service\WorkService */
	protected $workService;

	/**
	 * Constructor
	 *
	 * @param \phpbb\controller\helper				$helper				Controller helper object
	 * @param ContainerInterface					$container			Service container interface
	 * @param \phpbb\user							$user				User object
	 * @param \phpbb\request\request				$request			Request object
	 * @param \consim\core\service\ActionService	$actionService		ActionService object
	 * @param \consim\core\service\RouteService		$routeService		RouteService object
	 * @param \consim\core\service\UserService		$userService		UserService object
	 * @param \consim\core\service\UserSkillService	$userSkillService	UserSkillService object
	 * @param \consim\core\service\WorkService		$workService		WorkService object
	 * @return \consim\core\controller\RequestController
	 * @access public
	 */
	public function __construct(ContainerInterface $container,
		\phpbb\controller\helper $helper,
		\phpbb\user $user,
		\phpbb\request\request $request,
		\consim\core\service\ActionService $actionService,
		\consim\core\service\RouteService $routeService,
		\consim\core\service\UserService $userService,
		\consim\core\service\UserSkillService $userSkillService,
		\consim\core\service\WorkService $workService)
	{
		$this->container = $container;
		$this->helper = $helper;
		$this->user = $user;
		$this->request = $request;
		$this->actionService = $actionService;
		$this->routeService = $routeService;
		$this->userService = $userService;
		$this->userSkillService = $userSkillService;
		$this->workService = $workService;

		return $this;
	}

	/**
	 * Start travel
	 *
	 * @param $travel_id
	 * @return void
	 */
	public function startTravel($travel_id)
	{
		//Check the request
		if (!$this->is_valid($travel_id) || !check_link_hash($this->request->variable('hash', ''), 'travel_' . $travel_id))
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		//Load ConsimUser
		$consim_user = $this->userService->getCurrentUser();

		//Check, if user not active
		if($consim_user->getActive())
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		//Get Infos about the Route
		$route = $this->routeService->findRoute($consim_user->getLocationId(), $travel_id);

		$now = time();
		$this->container->get('consim.core.entity.action')
			->setUserId($consim_user->getUserId())
			->setLocationId($consim_user->getLocationId())
			->setStartTime($now)
			//TODO: Removed division 10!
			->setEndTime($now + ($route->getTime()/10))
			->setRouteId($route->getId())
			->setResult('')
			->insert();

		//$consim_user->setLocation($travel_id);
		//$consim_user->save();

		//Reload the Consim Index
		redirect($this->helper->route('consim_core_index'));
	}

	/**
	 * Start work
	 *
	 * return void
	 */
	public function startWork()
	{
		$work_id = $this->request->variable('work_id', 0);

		//Check the request
		if (!$this->is_valid($work_id) || !check_form_key('working'))
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}
		//Load ConsimUser
		$consim_user = $this->userService->getCurrentUser();

		//Check, if user not active
		if($consim_user->getActive())
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		//Get infos about work
		$work = $this->workService->getWork($work_id);
		$user_skills = $this->userSkillService->getCurrentUserSkills();

		//Check condition
		if(!$work->canUserWork($user_skills))
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		$now = time();
		$this->container->get('consim.core.entity.action')
			->setUserId($consim_user->getUserId())
			->setLocationId($consim_user->getLocationId())
			->setStartTime($now)
			->setEndTime($now + $work->getDuration())
			->setWorkId($work->getId())
			->setResult('')
			->insert();

		redirect($this->helper->route('consim_core_index'));
	}

	/**
	 * End work
	 *
	 * @return void
	 */
	public function endWork()
	{
		$action_id = $this->request->variable('action_id', 0);

		//Check the request
		if (!$this->is_valid($action_id) || !check_form_key('working_end'))
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		//Load ConsimUser
		$consim_user = $this->userService->getCurrentUser();

		//Check, if user active
		if(!$consim_user->getActive())
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		$action = $this->actionService->getCurrentAction();

		if($action->getStatus() != 2)
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}
		$action->userDone();

		redirect($this->helper->route('consim_core_work', array('action_id' => $action->getId())));
	}

	/**
	 * Is value valid?
	 *
	 * @param $value
	 * @return bool
	 */
	protected function is_valid($value)
	{
		return !empty($value) && preg_match('/^\w+$/', $value);
	}
}

