<?php
/**
* @package ConSim for phpBB3.1
*
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*/

namespace consim\core\operators;

use consim\core\entity\TravelLocation;
use consim\core\entity\Working;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Operator for all locations, that you can travel
*/
class ActionLists
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var ContainerInterface */
	protected $container;

	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_action_table;
	protected $consim_travel_table;

	/**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface	$db						Database object
	* @param ContainerInterface					$container				Service container interface
	* @param string								$consim_action_table	Name of the table used to store data
	* @param string								$consim_travel_table	Name of the table used to store data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db,
								ContainerInterface $container,
								$consim_action_table,
								$consim_travel_table)
	{
		$this->db = $db;
		$this->container = $container;
		$this->consim_action_table = $consim_action_table;
		$this->consim_travel_table = $consim_travel_table;
	}

	/**
	* Get all finished actions
	*
	* @access public
	*/
	public function finishedActions()
	{
		$sql = 'SELECT a.id, a.user_id, a.starttime, a.endtime, a.status,
					   t.start_location_id, t.end_location_id
			FROM ' . $this->consim_action_table . ' a
			LEFT JOIN ' . $this->consim_travel_table . ' t ON t.id = a.travel_id
			WHERE a.endtime <= '. time() .' AND a.status = 0';
		$result = $this->db->sql_query($sql);

		while($row = $this->db->sql_fetchrow($result))
		{
			if($row['start_location_id'] != NULL)
			{
				$this->container->get('consim.core.entity.travel')->import($row)->done();
			}
		}
		$this->db->sql_freeresult($result);
	}

	/**
	* Get current action from user
	*
	* @param int $user_id User ID
	* @return TravelLocation|Working|null
	* @access public
	*/
	public function getCurrentActionFromUser($user_id)
	{
		$action = false;

		$sql = 'SELECT a.id, a.user_id, a.starttime, a.endtime, a.travel_id, work_id, a.status
			FROM ' . $this->consim_action_table . ' a
			WHERE user_id = ' . (int) $user_id .' AND status = 0';
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if($row['travel_id'] > 0)
		{
			return $this->container->get('consim.core.entity.travel_location')->load($row['id']);
		}
		if($row['work_id'] > 0)
		{
			return $this->container->get('consim.core.entity.working')->load($row['id']);
		}

		return null;
	}

}
