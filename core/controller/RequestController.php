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
	/**
	 * Constructor
	 *
	 * @param \phpbb\controller\helper			$helper				Controller helper object
	 * @param ContainerInterface				$container			Service container interface
	 * @param \phpbb\user						$user				User object
	 * @param \phpbb\request\request			$request			Request object
	 * @return \consim\core\controller\RequestController
	 * @access public
	 */
	public function __construct(ContainerInterface $container,
		\phpbb\controller\helper $helper,
		\phpbb\user $user,
		\phpbb\request\request $request)
	{
		$this->container = $container;
		$this->helper = $helper;
		$this->user = $user;
		$this->request = $request;
	}

	public function startTravel($travel_id)
	{
		//Check the request
		if (!$this->is_valid($travel_id) || !check_link_hash($this->request->variable('hash', ''), 'travel_' . $travel_id))
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		//Load ConsimUser
		$consim_user = $this->container->get('consim.core.entity.consim_user')->load($this->user->data['user_id']);

		//Check, if user not active
		if($consim_user->getActive())
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		//Get Infos about the Route
		$route = $this->container->get('consim.core.entity.route')->load($consim_user->getLocationId(), $travel_id);

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

	public function startWork()
	{
		$work_id = $this->request->variable('work_id', 0);

		//Check the request
		if (!$this->is_valid($work_id) || !check_form_key('working'))
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}
		//Load ConsimUser
		$consim_user = $this->container->get('consim.core.entity.consim_user')->load($this->user->data['user_id']);

		//Check, if user not active
		if($consim_user->getActive())
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		//Get infos about work
		$work = $this->container->get('consim.core.entity.work')->load($work_id);
		$user_skills = $this->container->get('consim.core.operators.user_skills')->getUserSkills($consim_user->getUserId());

		//Check condition
		$can_work = true;
		if(($work->getCondition1Id() > 0 && $user_skills[$work->getCondition1Id()]->getValue() < $work->getCondition1Value()) ||
			($work->getCondition2Id() > 0 && $user_skills[$work->getCondition2Id()]->getValue() < $work->getCondition2Value()) ||
			($work->getCondition3Id() > 0 && $user_skills[$work->getCondition3Id()]->getValue() < $work->getCondition3Value())
		)
		{
			$can_work = false;
		}
		if($can_work === false)
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

	public function endWork()
	{
		$action_id = $this->request->variable('action_id', 0);
		//Check the request
		if (!$this->is_valid($action_id) || !check_form_key('working_end'))
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		//Load ConsimUser
		$consim_user = $this->container->get('consim.core.entity.consim_user')->load($this->user->data['user_id']);

		//Check, if user active
		if(!$consim_user->getActive())
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		$action = $this->container->get('consim.core.operators.action_lists')->getCurrentActionFromUser($this->user->data['user_id']);

		if($action->getStatus() != 2)
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}
		$action->userDone();

		redirect($this->helper->route('consim_core_work', array('work_id' => $action->getId())));
	}

	protected function is_valid($value)
	{
		return !empty($value) && preg_match('/^\w+$/', $value);
	}
}
