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
class AssetController extends AbstractController
{
	/**
	 * Constructor
	 *
	 * @param \phpbb\controller\helper              $helper Controller helper object
	 * @param \phpbb\user                           $user User object
	 * @param \phpbb\template\template              $template Template object
	 * @param \phpbb\request\request                $request Request object
	 * @param \consim\core\service\ActionService    $actionService ActionService object
	 * @param \consim\core\service\AssetService		$assetService		AssetService object
	 * @param \consim\core\service\InventoryService $inventoryService InventoryService object
	 * @param \consim\core\service\LocationService  $locationService LocationService object
	 * @param \consim\core\service\UserService      $userService UserService object
	 * @param \consim\core\service\UserSkillService $userSkillService UserSkillService object
	 * @param \consim\core\service\WeatherService   $weatherService WeatherService object
	 * @param \consim\core\service\widgetService    $widgetService WidgetService object
	 * @return \consim\core\controller\AssetController
	 * @access public
	 */
	public function __construct(
		\phpbb\controller\helper $helper,
		\phpbb\user $user,
		\phpbb\template\template $template,
		\phpbb\request\request $request,
		\consim\core\service\ActionService $actionService,
		\consim\core\service\AssetService $assetService,
		\consim\core\service\InventoryService $inventoryService,
		\consim\core\service\LocationService $locationService,
		\consim\core\service\UserService $userService,
		\consim\core\service\UserSkillService $userSkillService,
		\consim\core\service\WeatherService $weatherService,
		\consim\core\service\WidgetService $widgetService
	) {
		$this->helper = $helper;
		$this->user = $user;
		$this->template = $template;
		$this->request = $request;
		$this->actionService = $actionService;
		$this->assetService = $assetService;
		$this->inventoryService = $inventoryService;
		$this->locationService = $locationService;
		$this->userService = $userService;
		$this->userSkillService = $userSkillService;
		$this->weatherService = $weatherService;
		$this->widgetService = $widgetService;

		//Starting with the init
		$this->init();

		return $this;
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function indexAction()
	{
		$cashAssets = $this->assetService->getCurrentCashAsset();
		foreach ($cashAssets as $currency)
		{
			$this->template->assign_block_vars('asset_currencies', array(
				'NAME'			=> $currency->getName(),
				'SHORT_NAME'	=> $currency->getShortName(),
				'VALUE'			=> $currency->getValue(),
				'EXCHANGE_RATE'	=> '1 '. $currency->getShortName() .' : '. $currency->getExchangeRate() .' Cr',
				'TOTAL_VALUE'	=> $currency->getValue() * $currency->getExchangeRate(),
			));
		}

		$bondAssets = $this->assetService->getCurrentBondAsset();
		foreach ($bondAssets as $bond)
		{
			$this->template->assign_block_vars('asset_bonds', array(
				'NAME'			=> $bond->getName(),
				'SHORT_NAME'	=> $bond->getShortName(),
				'VALUE'			=> $bond->getValue(),
				'NOMINAL_VALUE'	=> $bond->getNominalValue(),
				'EXCHANGE_RATE'	=> '1 '. $bond->getShortName() .' : '. $bond->getExchangeRate() .' Cr',
				'TOTAL_VALUE'	=> $bond->getValue() * $bond->getExchangeRate(),
			));
		}

		// Send all data to the template file
		return $this->helper->render('consim_asset.html', $this->user->lang('CONSIM'));
	}
}