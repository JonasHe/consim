<?php
/**
 * @package ConSim for phpBB3.1
 *
 * @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace consim\core\service;

use consim\core\entity\Building;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Operator for Inventory
 */
class BuildingService
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var ContainerInterface */
	protected $container;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface		$db				Database object
	 * @param ContainerInterface					$container		Service container interface
	 * @param \phpbb\template\template				$template		Template object
	 * @param \phpbb\controller\helper				$helper			Controller helper object
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db,
		ContainerInterface $container,
		\phpbb\template\template $template,
		\phpbb\controller\helper $helper)
	{
		$this->db = $db;
		$this->container = $container;
		$this->template = $template;
		$this->helper = $helper;
	}

	/**
	 * Return the building
	 *
	 * @param $buildingId
	 * @return Building
	 */
	public function getBuilding($buildingId)
	{
		return $this->container->get('consim.core.entity.building')->load($buildingId);
	}

	/**
	 * @param int $location_id
	 * @param int $building_type_id
	 * @return Building
	 * @throws \consim\core\exception\out_of_bounds
	 */
	public function findBuilding($location_id, $building_type_id)
	{
		$sql = 'SELECT lb.id, lb.name, lb.description, b.id AS type_id, b.name AS type_name
			FROM ' . $this->container->getParameter('tables.consim.buildings') . ' lb
			LEFT JOIN ' . $this->container->getParameter('tables.consim.building_types') . ' b ON lb.type_id = b.id
			WHERE b.id = '. $building_type_id .'
				AND lb.location_id = '. $location_id;
		$result = $this->db->sql_query($sql);
		$data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($data === false)
		{
			throw new \consim\core\exception\out_of_bounds('id');
		}

		return $this->container->get('consim.core.entity.building')->import($data);
	}

	/**
	 * Get all buildings from location
	 *
	 * @param int $location_id Location ID
	 * @return \consim\core\entity\Building[]
	 * @access public
	 */
	public function getAllBuildings($location_id)
	{
		$entities = array();

		$sql = 'SELECT lb.id, lb.name, lb.description, b.id AS type_id, b.name AS type_name
			FROM ' . $this->container->getParameter('tables.consim.buildings') . ' lb
			LEFT JOIN ' . $this->container->getParameter('tables.consim.building_types') . ' b ON lb.type_id = b.id
			WHERE lb.location_id = ' . (int) $location_id;
		$result = $this->db->sql_query($sql);

		while($row = $this->db->sql_fetchrow($result))
		{
			$entities[] = $this->container->get('consim.core.entity.building')->import($row);
		}
		$this->db->sql_freeresult($result);

		return $entities;
	}

	/**
	 * @param int								$locationId
	 * @return BuildingService
	 */
	public function allLocationBuildingsToTemplate($locationId)
	{
		$buildings = $this->getAllBuildings($locationId);

		//Put all Buildings in the Template
		foreach ($buildings as $entity)
		{
			$building = array(
				'NAME'			=> ($entity->getName() != '')? '"' . $entity->getName() . '"' : '',
				'TYPE'  		=> $entity->getTypeName(),
				'URL'			=> $this->helper->route('consim_core_building',
					array(
						'location_id' => $locationId,
						'building_id' => $entity->getId(),
					)),
			);
			$this->template->assign_block_vars('buildings', $building);
		}

		return $this;
	}
}
