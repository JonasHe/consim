<?php
/**
 * @package ConSim for phpBB3.1
 *
 * @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace consim\core\service;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Operator for Inventory
 */
class WidgetService
{
	/** @var ContainerInterface */
	protected $container;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/**
	 * Constructor
	 *
	 * @param ContainerInterface				$container		Service container interface
	 * @param \phpbb\template\template			$template		Template object
	 * @param \phpbb\controller\helper			$helper			Controller helper object
	 * @access public
	 */
	public function __construct(ContainerInterface $container,
		\phpbb\template\template $template,
		\phpbb\controller\helper $helper)
	{
		$this->container = $container;
		$this->template = $template;
		$this->helper = $helper;
	}

	/**
	 *
	 * @param \consim\core\entity\UserSkill[]	$sortedUserSkills
	 */
	public function userSkillWidget(array $sortedUserSkills)
	{
		foreach ($sortedUserSkills as $cat => $skills)
		{
			$this->template->assign_block_vars('user_skill_groups', array(
				'NAME'			=> $cat,
			));

			/** @var \consim\core\entity\UserSkill $skill */
			foreach ($skills as $skill)
			{
				$this->template->assign_block_vars('user_skill_groups.skills', array(
					'NAME'			=> $skill->getName(),
					'VALUE'			=> $skill->getValue(),
				));
			}

		}
	}

	/**
	 * @param \consim\core\entity\InventoryItem[]	$inventory
	 */
	public function inventoryWidget(array $inventory)
	{
		foreach ($inventory as $item)
		{
			$this->template->assign_block_vars('inventory_widget', array(
				'ID'			=> $item->getId(),
				'NAME'			=> $item->getItemName(),
				'SHORT_NAME'	=> $item->getItemShortName(),
				'VALUE'			=> $item->getValue(),
			));
		}
	}

	/**
	 * @param \consim\core\entity\Location $userLocation
	 */
	public function locationWidget(\consim\core\entity\Location $userLocation)
	{
		$this->template->assign_vars(array(
			//Informations for current location and time
			'IS_LOCATION_WIDGET'			=> True,
			'USER_LOCATION_WIDGET'			=> $userLocation->getName(),
			'USER_LOCATION_TYPE_WIDGET'		=> $userLocation->getType(),
			'USER_LOCATION_URL_WIDGET'		=> $this->helper->route('consim_core_location', array('location_id' => $userLocation->getId())),
			'USER_PROVINCE_WIDGET'			=> $userLocation->getProvince(),
			'USER_COUNTRY_WIDGET'			=> $userLocation->getCountry(),
		));
	}

	/**
	 * @param \consim\core\entity\ConsimUser	$user
	 */
	public function experienceWidget(\consim\core\entity\ConsimUser $user)
	{
		$this->template->assign_vars(array(
			'IS_USER_EXPERIENCE_WIDGET'		=> True,
			'USER_EXPERIENCE_POINTS_WIDGET'	=> $user->getExperiencePoints(),
		));
	}

	/**
	 * @param \consim\core\entity\Action $action
	 */
	public function travelWidget(\consim\core\entity\Action $action)
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
			'SHOW_TRAVEL_WIDGET'					=> TRUE,
			'TRAVEL_WIDGET_START_LOCATION_NAME'		=> $start_location->getName(),
			'TRAVEL_WIDGET_START_LOCATION_TYPE'		=> $start_location->getType(),
			'TRAVEL_WIDGET_START_LOCATION_PROVINCE'	=> $start_location->getProvince(),
			'TRAVEL_WIDGET_START_LOCATION_COUNTRY'	=> $start_location->getCountry(),
			'TRAVEL_WIDGET_START_TIME'				=> date("d.m.Y - H:i:s", $action->getStartTime()),
			'TRAVEL_WIDGET_END_LOCATION_NAME'		=> $end_location->getName(),
			'TRAVEL_WIDGET_END_LOCATION_TYPE'		=> $end_location->getType(),
			'TRAVEL_WIDGET_END_LOCATION_PROVINCE'	=> $end_location->getProvince(),
			'TRAVEL_WIDGET_END_LOCATION_COUNTRY'	=> $end_location->getCountry(),
			'TRAVEL_WIDGET_END_TIME'				=> date("d.m.Y - H:i:s", $action->getEndTime()),
			'TRAVEL_WIDGET_COUNTDOWN'				=> date("i:s", $time),
		));
	}

	/**
	 * @param \consim\core\entity\Action $action
	 */
	public function workWidget(\consim\core\entity\Action $action)
	{
		$now = time();
		$time = $action->getEndTime() - $now;

		$working = $this->container->get('consim.core.entity.work')->load($action->getWorkId());
		$location = $this->container->get('consim.core.entity.location')->load($action->getLocationId());
		$building = $this->container->get('consim.core.entity.building')->find($location->getId(), $working->getBuildingTypeId());

		// Set output vars for display in the template
		$this->template->assign_vars(array(
			'SHOW_WORK_WIDGET'			=> TRUE,
			'WORK_WIDGET_IS_WORKING'	=> ($action->getStatus() == 0)? TRUE : FALSE,
			'WORK_WIDGET_IS_FINISHED'	=> ($action->getStatus() == 2)? TRUE : FALSE,
			'WORK_WIDGET_NAME'			=> $working->getName(),
			'WORK_WIDGET_BUILDING_NAME'	=> ($building->getName() != '')? '"' . $building->getName() . '"' : '',
			'WORK_WIDGET_BUILDING_TYPE'	=> $building->getTypeName(),
			'WORK_WIDGET_LOCATION_NAME'	=> $location->getName(),
			'WORK_WIDGET_TIME'			=> ($action->getStatus() == 0)? date("i:s", $time) : FALSE,
		));
	}
}
