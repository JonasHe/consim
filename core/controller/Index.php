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
use consim\core\entity\Skill;
use consim\core\entity\UserSkill;
use consim\core\entity\Work;
use consim\core\entity\WorkOutput;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Main controller
*/
class Index
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

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

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
	* Constructor
	*
	* @param \phpbb\config\config				$config			Config object
	* @param ContainerInterface					$container		Service container interface
	* @param \phpbb\controller\helper			$helper			Controller helper object
	* @param \phpbb\user						$user			User object
	* @param \phpbb\template\template			$template		Template object
	* @param \phpbb\request\request				$request		Request object
	* @param \phpbb\db\driver\driver_interface	$db				Database object
	* @return \consim\core\controller\Index
	* @access public
	*/
	public function __construct(\phpbb\config\config $config,
								ContainerInterface $container,
								\phpbb\controller\helper $helper,
								\phpbb\user $user,
								\phpbb\template\template $template,
								\phpbb\request\request $request,
								\phpbb\db\driver\driver_interface $db)
	{
		$this->config = $config;
		$this->container = $container;
		$this->helper = $helper;
		$this->user = $user;
		$this->template = $template;
		$this->request = $request;
		$this->db = $db;

		//Starting with the init
		$this->init();
	}

	/**
	* Display index
	*
	* @return null
	* @access public
	*/
	public function display()
	{
		// Is the form being submitted to us?
		// Delete UserProfile
		if ($this->request->is_set_post('delete'))
		{
			$sql = 'UPDATE ' . USERS_TABLE . '
				SET consim_register = 0
				WHERE user_id = ' . $this->user->data['user_id'];
			$this->db->sql_query($sql);

			$sql = 'DELETE FROM phpbb_consim_actions
				WHERE user_id = 2'. $this->user->data['user_id'];
			$this->db->sql_query($sql);

			$sql = 'DELETE FROM phpbb_consim_user_skills
				WHERE user_id = 2'. $this->user->data['user_id'];
			$this->db->sql_query($sql);

			$sql = 'DELETE FROM phpbb_consim_inventory_items
				WHERE user_id = 2'. $this->user->data['user_id'];
			$this->db->sql_query($sql);

			$sql = 'DELETE FROM phpbb_consim_user
				WHERE user_id = 2'. $this->user->data['user_id'];
			$this->db->sql_query($sql);

			//Leite den User weiter zum Consim Register
			redirect($this->helper->route('consim_core_register'));
		}

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

		return $this->showLocation();
	}

	/**
	* Display all traveling routes
	*
	* @param Action $action
	* @return null
	* @access private
	*/
	private function showTraveling($action)
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
	 * @param Action $action
	 * @return null
	 * @access private
	 */
	private function showWorking($action)
	{
		$now = time();
		$time = $action->getEndTime() - $now;

		$working = $this->container->get('consim.core.entity.work')->load($action->getWorkId());
		$location = $this->container->get('consim.core.entity.location')->load($action->getLocationId());
		$building = $this->container->get('consim.core.entity.building')->find($location->getId(), $working->getBuildingTypeId());

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
		
		if($action->getStatus() == 1)
		{
			$this->template->assign_vars(array(
				'IS_WORK_SUCCESSFUL'		=> ($action->getSuccessfulTrials() < Work::neededSuccessfulTrials)?  FALSE : TRUE,
				'WORK_SUCCESSFUL_TRIALS'	=> $action->getSuccessfulTrials(),
				'WORK_TRIALS_NUMBER'		=> Work::trialsNumber,
			));
		}

		// Set output vars for display in the template
		$this->template->assign_vars(array(
			'SHOW_WORKING'			=> TRUE,
			'IS_WORKING'			=> ($action->getStatus() == 0)? TRUE : FALSE,
			'WORK_NAME'				=> $working->getName(),
			'WORK_CONDITION_TYPE'	=> $working->getConditionName(),
			'WORK_CONDITION_VALUE'	=> $working->getConditionValue(),
			'WORK_OUTPUT_TYPE'		=> $working->getOutputName(),
			'WORK_OUTPUT_VALUE'		=> $working->getOutputValue(),
			'WORK_EXPERIENCE_POINTS'=> $working->getExperiencePoints(),
			'WORK_BUILDING_NAME'	=> ($building->getName() != '')? '"' . $building->getName() . '"' : '',
			'WORK_BUILDING_TYPE'	=> $building->getTypeName(),
			'WORK_LOCATION_NAME'	=> $location->getName(),
			'WORK_TIME'				=> ($action->getStatus() == 0)? date("i:s", $time) : FALSE,

		));
	}

	/**
	* Display a location
	*
	* @param int $location_id
	* @return null
	* @access public
	*/
	public function showLocation($location_id = 0)
	{
		//must be an integer
		$location_id = (int) $location_id;

		$location = $this->container->get('consim.core.entity.location');
		$location_op = $this->container->get('consim.core.operators.locations');

		//location from location_id or from position of user?
		if($location_id === 0 || $location_id === $this->consim_user->getLocationId())
		{
			$location = $this->consim_user_location;

			if(!$this->consim_user->getActive())
			{
				//Create the Travelpopup
				$location_op->setAllRouteDestinationsToTemplate($location->getId(), $this->template, $this->helper);
			}
		}
		else
		{
			$location->load($location_id);
		}
		$buildings = $location_op->getAllBuildings($location->getId());

		//Put all Buildings in the Template
		foreach ($buildings as $entity)
		{
			$building = array(
				'NAME'			=> ($entity->getName() != '')? '"' . $entity->getName() . '"' : '',
				'TYPE'  		=> $entity->getTypeName(),
				'URL'			=> $this->helper->route('consim_core_building',
													array(
														'location_id' => $location->getId(),
														'building_id' => $entity->getId(),
													)),
			);

			$this->template->assign_block_vars('buildings', $building);
		}

		// Set output vars for display in the template
		$this->template->assign_vars(array(
			'CAN_TRAVEL'					=> ($location->getId() === $this->consim_user->getLocationId()
												&& !$this->consim_user->getActive())? TRUE : FALSE,
			'LOCATION'						=> $location->getName(),
			'LOCATION_DESC'					=> $location->getDescription(),
			'LOCATION_IMAGE'				=> $location->getImage(),
			'LOCATION_TYPE'					=> $location->getType(),
			'PROVINCE'						=> $location->getProvince(),
			'COUNTRY'						=> $location->getCountry(),
		));

		// Send all data to the template file
		return $this->helper->render('consim_index.html', $this->user->lang('CONSIM'));
	}

	/**
	* Display a building in a location
	*
	* @param int $location_id
	* @param int $building_id
	* @return null
	* @access public
	*/
	public function showLocationBuilding($location_id, $building_id)
	{
		//must be an integer
		$location_id = (int) $location_id;
		$building_id = (int) $building_id;

		if($location_id === 0 || $building_id === 0)
		{
			redirect($this->helper->route('consim_core_location', array('location_id' => $location_id)));
		}

		$location = $this->container->get('consim.core.entity.location')->load($location_id);
		$building = $this->container->get('consim.core.entity.building')->load($building_id);

		//add location to navbar
		$this->add_navlinks($location->getName(), $this->helper->route('consim_core_location', array('location_id' => $location->getId())));

		//Get all Works
		$works = $this->container->get('consim.core.operators.works')->getWorks($building->getTypeId());
		foreach ($works as $work)
		{
			$s_hidden_fields = build_hidden_fields(array(
				'work_id'		=> $work->getId(),
			));

			$can_work = true;
			if($this->consim_user->getActive() ||
				($work->getCondition1Id() > 0 && $this->consim_user_skills[$work->getCondition1Id()]->getValue() > $work->getCondition1Value()) ||
				($work->getCondition2Id() > 0 && $this->consim_user_skills[$work->getCondition2Id()]->getValue() > $work->getCondition2Value()) ||
				($work->getCondition3Id() > 0 && $this->consim_user_skills[$work->getCondition3Id()]->getValue() > $work->getCondition3Value())
			)
			{
				$can_work = true;
			}

			$this->template->assign_block_vars('works', array(
				'NAME'					=> $work->getName(),
				'DURATION'				=> date("i:s", $work->getDuration()),
				'CONDITION_1_TYPE'		=> $work->getCondition1Name(),
				'CONDITION_1_TRIALS'	=> $work->getCondition1Trials(),
				'CONDITION_1_VALUE'		=> $work->getCondition1Value(),
				'CONDITION_2_TYPE'		=> $work->getCondition2Name(),
				'CONDITION_2_TRIALS'	=> $work->getCondition2Trials(),
				'CONDITION_2_VALUE'		=> $work->getCondition2Value(),
				'CONDITION_3_TYPE'		=> $work->getCondition3Name(),
				'CONDITION_3_TRIALS'	=> $work->getCondition3Trials(),
				'CONDITION_3_VALUE'		=> $work->getCondition3Value(),
				'EXPERIENCE_POINTS'		=> implode("/", $work->getExperiencePoints()),
				'CAN_WORK'				=> $can_work,
				'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
			));

			foreach ($work->getSortedOutputs() as $type => $outputs)
			{
				$this->template->assign_block_vars('works.outputs', array(
					'TYPE'			=> $type,
				));

				/** @var WorkOutput $output */
				foreach ($outputs as $output)
				{
					$this->template->assign_block_vars('works.outputs.types', array(
						'VALUE'			=> $output->getOutputValue(),
					));
				}
			}
		}

		add_form_key('working');
		// Set output vars for display in the template
		$this->template->assign_vars(array(
			'BUILDING_NAME'         => ($building->getName() != '')? '"' . $building->getName() . '"' : '',
			'BUILDING_DESCRIPTION'  => ($building->getDescription() != '')? '' . $building->getDescription() . '' : '',
			'BUILDING_TYP'          => $building->getTypeName(),
			'LOCATION'              => $location->getName(),
			'BACK_TO_LOCATION'      => $this->helper->route('consim_core_location', array('location_id' => $location_id)),
			'S_WORK_ACTION'			=> $this->helper->route('consim_core_work_start'),
		));

		// Send all data to the template file
		return $this->helper->render('consim_building.html', $this->user->lang('CONSIM'));
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

	public function showAction($action_id)
	{
		$action = $this->container->get('consim.core.entity.action')->load($action_id);

		//check if the user is owner of action
		if($action->getUserId() != $this->consim_user->getUserId())
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

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

		return null;
	}

	/**
	* Initiated all important variable
	* and check if it a consim-user
	*
	* @return null
	* @access private
	*/
	private function init()
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
	 * Adding link at navlinks
	 *
	 * @param string $name
	 * @param string $route
	 * @return null
	 * @access public
	 */
	private function add_navlinks($name = '', $route = '')
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
}
