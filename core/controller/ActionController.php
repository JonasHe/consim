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
	* @return \consim\core\controller\ActionController
	* @access public
	*/
	public function __construct(\phpbb\config\config $config,
								ContainerInterface $container,
								\phpbb\controller\helper $helper,
								\phpbb\user $user,
								\phpbb\template\template $template,
								\phpbb\request\request $request)
	{
		$this->config = $config;
		$this->container = $container;
		$this->helper = $helper;
		$this->user = $user;
		$this->template = $template;
		$this->request = $request;

		$this->init();
	}

	public function showActivity()
	{
		if($this->consim_user->getActive())
		{
			//get current action
			$action = $this->container->get('consim.core.operators.action_lists')->getCurrentActionFromUser($this->user->data['user_id']);

			if($action->getRouteId() > 0)
			{
				$this->showTraveling($action);

				// Send all data to the template file
				return $this->helper->render('consim_traveling.html', $this->user->lang('CONSIM'));
			}
			// is user working?
			if($action->getWorkId() > 0)
			{
				$this->showWorking($action);

				// Send all data to the template file
				return $this->helper->render('consim_working.html', $this->user->lang('CONSIM'));
			}
		}
		return null;
	}

	public function showWork($work_id)
	{
		$work = $this->container->get('consim.core.entity.action')->load($work_id);

		//check if the user is owner of action
		if($work->getUserId() != $this->consim_user->getUserId() || $work->getWorkId() == 0)
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		$this->showWorking($work);

		// Send all data to the template file
		return $this->helper->render('consim_working.html', $this->user->lang('CONSIM'));
	}

	public function showTravel($travel_id)
	{
		$travel = $this->container->get('consim.core.entity.action')->load($travel_id);

		//check if the user is owner of action
		if($travel->getUserId() != $this->consim_user->getUserId() || $travel->getRouteId() == 0)
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		$this->showTraveling($travel);

		// Send all data to the template file
		return $this->helper->render('consim_traveling.html', $this->user->lang('CONSIM'));
	}
}
