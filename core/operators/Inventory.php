<?php
/**
 * @package ConSim for phpBB3.1
 *
 * @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace consim\core\operators;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Operator for Inventory
 */
class Inventory
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var ContainerInterface */
	protected $container;

	/**
	 * The database table the consim user data are stored in
	 * @var string
	 */
	protected $consim_inventory_table;
	protected $consim_inventory_item_table;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface	$db								Database object
	 * @param ContainerInterface					$container						Service container interface
	 * @param string								$consim_route_table				Name of the table used to store data
	 * @param string								$consim_location_table			Name of the table used to store data
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db,
								ContainerInterface $container,
								$consim_inventory_table,
								$consim_inventory_item_table)
	{
		$this->db = $db;
		$this->container = $container;
		$this->consim_inventory_table = $consim_inventory_table;
		$this->consim_inventory_item_table = $consim_inventory_item_table;
	}

	/**
	 * Get all Items from Inventory of User
	 *
	 * @param int $user_id User ID
	 * @return \consim\core\entity\Inventory[]
	 * @access public
	 */
	public function getInventory($user_id)
	{
		$items = array();

		$sql = 'SELECT i.id, i.value,
					item.id AS item_id, item.name AS item_name, item.short_name AS item_short_name
				FROM ' . $this->consim_inventory_table . ' i
				LEFT JOIN ' . $this->consim_inventory_item_table . ' item ON item.id = i.item_id
				WHERE i.user_id = ' . (int) $user_id;
		$result = $this->db->sql_query($sql);

		while($row = $this->db->sql_fetchrow($result))
		{
			$items[] = $this->container->get('consim.core.entity.inventory')->import($row);
		}
		$this->db->sql_freeresult($result);

		return $items;
	}
}