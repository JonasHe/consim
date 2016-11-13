<?php
/**
* @package ConSim for phpBB3.1
*
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*/

namespace consim\core\service;

use consim\core\entity\Action;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Operator for all locations, that you can travel
*/
class ActionService
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var ContainerInterface */
	protected $container;

	/** @var  \consim\core\service\UserService */
	protected $userService;

	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_action_table;

	/** @var  \consim\core\entity\Action|null */
	protected $currentAction = null;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface		$db						Database object
	 * @param ContainerInterface					$container				Service container interface
	 * @param \consim\core\service\UserService		$userService			UserService object
	 * @param string								$consim_travel_table	Name of the table used to store data
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db,
		ContainerInterface $container,
		\consim\core\service\UserService $userService,
		$consim_action_table)
	{
		$this->db = $db;
		$this->container = $container;
		$this->userService = $userService;
		$this->consim_action_table = $consim_action_table;
	}

	/**
	 * Return current action of current user
	 *
	 * @return Action
	 */
	public function getCurrentAction()
	{
		if(null === $this->currentAction)
		{
			$this->currentAction = $this->getCurrentActionFromUser($this->userService->getCurrentUser()->getUserId());
		}

		return $this->currentAction;
	}

	/**
	 * Return action from actionId
	 *
	 * @param $actionId
	 * @return Action
	 */
	public function getAction($actionId)
	{
		if(null !== $this->currentAction && $this->currentAction->getId() == $actionId)
		{
			return $this->currentAction;
		}

		return $this->container->get('consim.core.entity.action')->load($actionId);
	}

	/**
	* Get all finished actions
	*
	* @access public
	*/
	public function finishedActions()
	{
		$sql = 'SELECT a.id, a.user_id, a.location_id, a.starttime, a.endtime, a.route_id, a.work_id, a.result, a.successful_trials, a.status
			FROM ' . $this->consim_action_table . ' a
			WHERE a.endtime <= '. time() .' AND a.status = '. Action::active;
		$result = $this->db->sql_query($sql);

		while($row = $this->db->sql_fetchrow($result))
		{
			$this->container->get('consim.core.entity.action')->import($row)->done();
		}
		$this->db->sql_freeresult($result);
	}

	/**
	* Get current action from user
	*
	* @param int $user_id User ID
	* @return Action
	* @access public
	*/
	public function getCurrentActionFromUser($user_id)
	{
		if(null !== $this->currentAction && $this->userService->getCurrentUser()->getUserId() == $user_id)
		{
			return $this->currentAction;
		}

		$sql = 'SELECT a.id, a.user_id, a.location_id, a.starttime, a.endtime, a.route_id, a.work_id, a.result, a.successful_trials, a.status
			FROM ' . $this->consim_action_table . ' a
			WHERE user_id = ' . (int) $user_id .' AND status = '. Action::active .' OR status = '. Action::mustConfirm;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);
		
		return $this->container->get('consim.core.entity.action')->import($row);
	}

	/**
	 *
	 * @param int $user_id
	 * @param int $number Number of Action.
	 * @param int $start
	 * @return Action[]
	 */
	public function getActions($user_id, $number = 25, $start = 0)
	{
		$action = array();

		$sql = 'SELECT a.id, a.user_id, a.location_id, a.starttime, a.endtime, a.route_id, a.work_id, a.result, a.successful_trials, a.status
			FROM ' . $this->consim_action_table . ' a
			WHERE user_id = ' . (int) $user_id .'
			ORDER BY a.endtime DESC';
		$result = $this->db->sql_query_limit($sql, $number, $start);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$action[] = $this->container->get('consim.core.entity.action')->import($row);
		}
		$this->db->sql_freeresult($result);

		return $action;
	}

	/**
	 * Return number of al actions
	 *
	 * @param int $user_id
	 * @return int
	 */
	public function getNumberOfActions($user_id)
	{
		$sql = 'SELECT COUNT(id) AS num_action
			FROM ' . $this->consim_action_table . '
			WHERE user_id = '. (int) $user_id;
		$result = $this->db->sql_query($sql);
		$total_actions = (int) $this->db->sql_fetchfield('num_posts');
		$this->db->sql_freeresult($result);

		return $total_actions;
	}
}
