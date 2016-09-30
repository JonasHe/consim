<?php
/**
 *
 * @package ConSim for phpBB3.1
 * @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace consim\core\controller;

use consim\core\entity\WorkOutput;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Main controller
 */
class LocationController extends AbstractController
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config				$config			Config object
	 * @param ContainerInterface				$container		Service container interface
	 * @param \phpbb\controller\helper			$helper			Controller helper object
	 * @param \phpbb\user						$user			User object
	 * @param \phpbb\template\template			$template		Template object
	 * @param \phpbb\request\request			$request		Request object
	 * @param \phpbb\db\driver\driver_interface	$db				Database object
	 * @return \consim\core\controller\LocationController
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
	 * Display a location
	 *
	 * @param int $location_id
	 * @return null
	 * @access public
	 */
	public function showLocation($location_id = 0)
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
				WHERE user_id = '. $this->user->data['user_id'];
			$this->db->sql_query($sql);

			$sql = 'DELETE FROM phpbb_consim_user_skills
				WHERE user_id = '. $this->user->data['user_id'];
			$this->db->sql_query($sql);

			$sql = 'DELETE FROM phpbb_consim_inventory_items
				WHERE user_id = '. $this->user->data['user_id'];
			$this->db->sql_query($sql);

			$sql = 'DELETE FROM phpbb_consim_user
				WHERE user_id = '. $this->user->data['user_id'];
			$this->db->sql_query($sql);

			//Leite den User weiter zum Consim Register
			redirect($this->helper->route('consim_core_register'));
		}

		//must be an integer
		$location_id = (int) $location_id;

		$location = $this->container->get('consim.core.entity.location');
		$location_op = $this->container->get('consim.core.service.locations');

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
		$works = $this->container->get('consim.core.service.works')->getWorks($building->getTypeId());
		foreach ($works as $work)
		{
			$s_hidden_fields = build_hidden_fields(array(
				'work_id'		=> $work->getId(),
			));

			$can_work = true;
			if($this->consim_user->getActive() ||
				($work->getCondition1Id() > 0 && $this->consim_user_skills[$work->getCondition1Id()]->getValue() < $work->getCondition1Value()) ||
				($work->getCondition2Id() > 0 && $this->consim_user_skills[$work->getCondition2Id()]->getValue() < $work->getCondition2Value()) ||
				($work->getCondition3Id() > 0 && $this->consim_user_skills[$work->getCondition3Id()]->getValue() < $work->getCondition3Value())
			)
			{
				$can_work = false;
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
					'TYPE'			=> $outputs['name'],
				));

				/** @var WorkOutput[] $outputs */
				for($i=0; $i < 5; $i++)
				{
					$this->template->assign_block_vars('works.outputs.types', array(
						'VALUE'			=> (isset($outputs[$i]))? $outputs[$i]->getOutputValue() : 0,
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
}
