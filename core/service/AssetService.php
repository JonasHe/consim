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

	/** @var  \consim\core\service\UserService */
	protected $userService;

	//
	const CURRENCY_TYPE = 1;
	const BOND_TYPE = 2;
	const SHARE_TYPE = 3;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface		$db								Database object
	 * @param ContainerInterface					$container						Service container interface
	 * @param \consim\core\service\UserService		$userService					UserService object
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db,
		ContainerInterface $container,
		\consim\core\service\UserService $userService)
	{
		$this->db = $db;
		$this->container = $container;
		$this->userService = $userService;
	}

	/**
	 * Get cash asset of current user
	 *
	 * @return \consim\core\entity\UserAsset[]
	 */
	public function getCurrentCashAsset()
	{
		$user = $this->userService->getCurrentUser();

		$cashAsset = array();
		$sql = 'SELECT ua.id, ua.user_id, ua.asset_id, a.type_id, at.name as type_name, a.name, a.short_name, ua.value
			FROM '. $this->container->getParameter('tables.consim.users_assets') .' ua
			LEFT JOIN '. $this->container->getParameter('tables.consim.assets') .' a ON a.id = ua.asset_id 
			LEFT JOIN '. $this->container->getParameter('tables.consim.asset_types') .' at ON at.id = a.type_id
			WHERE ua.user_id = '. $user->getUserId() .' AND a.type_id = '. self::CURRENCY_TYPE;
		$result = $this->db->sql_query($sql);

		while($row = $this->db->sql_fetchrow($result))
		{
			$cashAsset[] = $this->container->get('consim.core.entity.user_asset')->import($row);
		}
		$this->db->sql_freeresult($result);

		return $cashAsset;
	}

	/**
	 * @return \consim\core\entity\Asset[]
	 */
	public function getAllCurrencies()
	{
		$currencies = array();
		$sql = 'SELECT a.id, a.type_id, at.name as type_name, a.name, a.short_name
			FROM '. $this->container->getParameter('tables.consim.assets') .' a
			LEFT JOIN '. $this->container->getParameter('tables.consim.asset_types') .' at ON at.id = a.type_id 
			WHERE a.type_id = '. self::CURRENCY_TYPE;
		$result = $this->db->sql_query($sql);

		while($row = $this->db->sql_fetchrow($result))
		{
			$currencies[] = $this->container->get('consim.core.entity.asset')->import($row);
		}
		$this->db->sql_freeresult($result);

		return $currencies;
	}

	/**
	 * @param int $currency_id
	 * @return \consim\core\entity\Asset
	 */
	public function getCurrency($currency_id)
	{
		return $this->container->get('consim.core.entity.asset')->load($currency_id);
	}

	public function getAllBonds()
	{
		$bonds = array();
		$sql = 'SELECT a.id, a.type_id, at.type_name, a.name, a.short_names
			FROM '. $this->container->getParameter('tables.consim.assets') .' a
			LEFT JOIN '. $this->container->getParameter('tables.consim.asset_types') .' at ON at.id = a.type_id 
			WHERE a.type_id = '. self::BOND_TYPE;
		$result = $this->db->sql_query($sql);

		while($row = $this->db->sql_fetchrow($result))
		{
			$bonds[] = $this->container->get('consim.core.entity.asset')->import($row);
		}
		$this->db->sql_freeresult($result);

		return $bonds;
	}

	/**
	 * @param int $bond_id
	 * @return \consim\core\entity\Asset
	 */
	public function getBond($bond_id)
	{
		return $this->container->get('consim.core.entity.asset')->load($bond_id);
	}

	public function setStartAssets($user_id, $country)
	{
		$sql = 'SELECT id
				FROM ' . $this->container->getParameter('tables.consim.assets') . '
				WHERE type_id = '. self::CURRENCY_TYPE .' or type_id = '. self::BOND_TYPE;
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

			$insert[] = array('user_id' => (int) $user_id, 'asset_id' => (int) $row['id'], 'value' => $value);
			/**$this->container->get('consim.core.entity.inventory_item')
				->insert($user_id, $row['id'], $value);*/
		}
		$this->db->sql_freeresult($result);

		$this->db->sql_multi_insert($this->container->getParameter('tables.consim.users_assets'), $insert);
	}
}