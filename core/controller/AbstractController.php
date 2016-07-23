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
	 * @return null
	 * @access protected
	 */
	protected function init()
	{
		if($this->user->data['consim_register'] == 0)
		{
			redirect($this->helper->route('consim_core_register'));
			return;
		}

		// Add language file
		$this->user->add_lang_ext('consim/core', 'consim_common');
		$this->add_navlinks();

		//Check all finished Actions
		$this->container->get('consim.core.operators.action_lists')->finishedActions();

		//Get the ConSim-User
		$this->consim_user = $this->container->get('consim.core.entity.consim_user')->load($this->user->data['user_id']);

		// get User Skill and add to template
		$user_skills_container = $this->container->get('consim.core.operators.user_skills');
		$this->consim_user_skills = $user_skills_container->getUserSkills($this->user->data['user_id']);

		foreach ($user_skills_container->sortSkillsByCategory($this->consim_user_skills) as $cat => $skills)
		{
			$this->template->assign_block_vars('user_skill_groups', array(
				'NAME'			=> $cat,
			));

			/** @var UserSkill $skill */
			foreach ($skills as $skill)
			{
				$this->template->assign_block_vars('user_skill_groups.skills', array(
					'NAME'			=> $skill->getName(),
					'VALUE'			=> $skill->getValue(),
				));
			}

		}

		// Get inventory and add to template
		$inventory = $this->container->get('consim.core.operators.inventories')->getInventory($this->consim_user->getUserId());
		foreach ($inventory as $item)
		{
			$this->template->assign_block_vars('inventory', array(
				'ID'			=> $item->getId(),
				'NAME'			=> $item->getItemName(),
				'SHORT_NAME'	=> $item->getItemShortName(),
				'VALUE'			=> $item->getValue(),
			));
		}

		//Get User-Location
		$this->consim_user_location = $this->container->get('consim.core.entity.location')->load($this->consim_user->getLocationId());

		//Is User active?
		if($this->consim_user->getActive())
		{
			//get current action
			$action = $this->container->get('consim.core.operators.action_lists')->getCurrentActionFromUser($this->user->data['user_id']);
			//Is User traveling?

			if($action->getRouteId() > 0)
			{
				$this->showTraveling($action);
			}
			// is user working?
			if($action->getWorkId() > 0)
			{
				$this->showWorking($action);
			}
		}

		// Set output vars for display in the template
		$this->template->assign_vars(array(
			//Informations for current location and time
			'TIME'							=> date("d.m.Y - H:i:s", time()),
			'USER_LOCATION'					=> $this->consim_user_location->getName(),
			'USER_LOCATION_TYPE'			=> $this->consim_user_location->getType(),
			'USER_LOCATION_URL'				=> $this->helper->route('consim_core_location', array('location_id' => $this->consim_user_location->getId())),
			'USER_PROVINCE'					=> $this->consim_user_location->getProvince(),
			'USER_COUNTRY'					=> $this->consim_user_location->getCountry(),
			'USER_EXPERIENCE_POINTS'		=> $this->consim_user->getExperiencePoints(),
			'GO_TO_INFORMATION'				=> $this->helper->route('consim_core_activity'),
			'U_OVERVIEW'					=> $this->helper->route('consim_core_index'),
		));
	}

	/**
	 * Display all traveling routes
	 *
	 * @param \consim\core\entity\Action $action
	 * @return null
	 * @access protected
	 */
	protected function showTraveling($action)
	{
		$now = time();
		$time = $action->getEndTime() - $now;

		$route = $this->container->get('consim.core.entity.route')->load($action->getRouteId());
		$start_location = $this->container->get('consim.core.entity.location')->load($action->getLocationId());
		$end_location = $this->container->get('consim.core.entity.location');
		if($action->getLocationId() == $route->getStartLocationId())
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
			'START_TIME'                => date("d.m.Y - H:i:s", $action->getStartTime()),
			'END_LOCATION_NAME'         => $end_location->getName(),
			'END_LOCATION_IMAGE'        => $end_location->getImage(),
			'END_LOCATION_TYPE'         => $end_location->getType(),
			'END_LOCATION_PROVINCE'     => $end_location->getProvince(),
			'END_LOCATION_COUNTRY'      => $end_location->getCountry(),
			'END_TIME'                  => date("d.m.Y - H:i:s", $action->getEndTime()),
			'COUNTDOWN'                 => date("i:s", $time),
		));
	}

	/**
	 * Display Working page
	 *
	 * @param \consim\core\entity\Action $action
	 * @return null
	 * @access protected
	 */
	protected function showWorking($action)
	{
		$now = time();
		$time = $action->getEndTime() - $now;

		$working = $this->container->get('consim.core.entity.work')->load($action->getWorkId());
		$location = $this->container->get('consim.core.entity.location')->load($action->getLocationId());
		$building = $this->container->get('consim.core.entity.building')->find($location->getId(), $working->getBuildingTypeId());

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

		$result = array();
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

		//set output to template
		foreach ($working->getSortedOutputs() as $type => $outputs)
		{
			$this->template->assign_block_vars('work_outputs', array(
				'TYPE'			=> $outputs['name'],
				'VALUE'			=> (isset($result) && !empty($result['outputs'][$type]))? $result['outputs'][$type]['value'] : 0,
			));
			print_r($result['outputs']);

			/** @var WorkOutput[] $outputs */
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
			'IS_WORKING'				=> ($action->getStatus() == 0)? TRUE : FALSE,
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
			'WORK_TIME'					=> ($action->getStatus() == 0)? date("i:s", $time) : FALSE,
		));
	}

	/**
	 * Adding link at navlinks
	 *
	 * @param string $name
	 * @param string $route
	 * @return null
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
