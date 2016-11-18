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
 * Operator for all locations, that you can travel
 */
class AssetService
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var ContainerInterface */
	protected $container;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface		$db								Database object
	 * @param ContainerInterface					$container						Service container interface
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db,
		ContainerInterface $container)
	{
		$this->db = $db;
		$this->container = $container;
	}

	public function setStartAssets($user_id, $country)
	{
		$sql = 'SELECT id
				FROM ' . $this->container->getParameter('tables.consim.assets') . '
				WHERE type_id = 1 or type_id = 2';
		$result = $this->db->sql_query($sql);

		$insert = array();
		while($row = $this->db->sql_fetchrow($result))
		{
			$value = 0;

			//set start value for country of birth
			if($row['id'] == 1 && $country == 'bak' || //1 = rubel
				$row['id'] == 2 && $country == 'sur' || //2 = dinar
				$row['id'] == 3 && $country == 'frt') //3 = dollar
			{
				$value = 50;
			}

			$insert[] = array('user_id' => $user_id, 'asset_id' => $row['id'], $value);
			/**$this->container->get('consim.core.entity.inventory_item')
				->insert($user_id, $row['id'], $value);*/
		}
		$this->db->sql_freeresult($result);

		$this->db->sql_multi_insert($this->container->getParameter('tables.consim.users_assets'), $insert);
	}
}