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
class LocationController extends AbstractController
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \consim\core\service\BuildingService */
	protected $buildingService;

	/** @var \consim\core\service\MapService */
	protected $mapService;

	/** @var \consim\core\service\WorkService */
	protected $workService;

	/**
	 * Constructor
	 *
	 * @param \phpbb\controller\helper				$helper				Controller helper object
	 * @param \phpbb\user							$user				User object
	 * @param \phpbb\template\template				$template			Template object
	 * @param \phpbb\request\request				$request			Request object
	 * @param \phpbb\db\driver\driver_interface		$db					Database object
	 * @param \consim\core\service\ActionService	$actionService		ActionService object
	 * @param \consim\core\service\BuildingService	$buildingService	BuildingService object
	 * @param \consim\core\service\InventoryService	$inventoryService	InventoryService object
	 * @param \consim\core\service\LocationService	$locationService	LocationService object
	 * @param \consim\core\service\MapService		$mapService			MapService object
	 * @param \consim\core\service\UserService		$userService		UserService object
	 * @param \consim\core\service\UserSkillService	$userSkillService	UserSkillService object
	 * @param \consim\core\service\WeatherService	$weatherService		WeatherService object
	 * @param \consim\core\service\widgetService	$widgetService		WidgetService object
	 * @param \consim\core\service\WorkService		$workService		WorkService object
	 * @return \consim\core\controller\LocationController
	 * @access public
	 */
	public function __construct(\phpbb\controller\helper $helper,
		\phpbb\user $user,
		\phpbb\template\template $template,
		\phpbb\request\request $request,
		\phpbb\db\driver\driver_interface $db,
		\consim\core\service\ActionService $actionService,
		\consim\core\service\BuildingService $buildingService,
		\consim\core\service\InventoryService $inventoryService,
		\consim\core\service\LocationService $locationService,
		\consim\core\service\MapService $mapService,
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
		$this->db = $db;
		$this->actionService = $actionService;
		$this->buildingService = $buildingService;
		$this->inventoryService = $inventoryService;
		$this->locationService = $locationService;
		$this->mapService = $mapService;
		$this->userService = $userService;
		$this->userSkillService = $userSkillService;
		$this->weatherService = $weatherService;
		$this->widgetService = $widgetService;
		$this->workService = $workService;

		//Starting with the init
		$this->init();
		return $this;
	}

	/**
	 * Display a location
	 *
	 * @param int $location_id
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @access public
	 */
	public function showLocationAction($location_id = 0)
	{
		// Is the form being submitted to us?
		// Delete UserProfile
		// TODO: delete; its only for debug
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

		$location = null;
		$consimUser = $this->userService->getCurrentUser();
		//location from location_id or from position of user?
		if($location_id === 0 || $location_id === $consimUser->getLocationId())
		{
			$location = $this->locationService->getCurrentLocation();

			if(!$consimUser->getActive())
			{
				//Create the Travelpopup
				$this->locationService->setAllRouteDestinationsToTemplate($location->getId(), $this->template, $this->helper);
			}
		}
		else
		{
			$location = $this->locationService->getLocation($location_id);
		}

		//Set all building from location to template
		$this->buildingService->allLocationBuildingsToTemplate($location->getId());

		//Display the map
		$this->mapService->showMap("consimMap",
			"mainMap",
			'width: 750px; height: 511px !important;',
			array('no_additional_buildings', 'no_zoom'),
			3,
			3
		);

		// Set output vars for display in the template
		$this->template->assign_vars(array(
			'CAN_TRAVEL'					=> ($location->getId() === $consimUser->getLocationId()
				&& !$consimUser->getActive())? TRUE : FALSE,
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
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @access public
	 */
	public function showLocationBuildingAction($location_id, $building_id)
	{
		//must be an integer
		$location_id = (int) $location_id;
		$building_id = (int) $building_id;

		if($location_id === 0 || $building_id === 0)
		{
			redirect($this->helper->route('consim_core_location', array('location_id' => $location_id)));
		}

		$location = $this->locationService->getLocation($location_id);
		$building = $this->buildingService->getBuilding($building_id);

		//add location to navbar
		$this->add_navlinks($location->getName(), $this->helper->route('consim_core_location', array('location_id' => $location->getId())));

		//Show all Works
		$this->workService->allWorksToTemplate($building->getTypeId());

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
