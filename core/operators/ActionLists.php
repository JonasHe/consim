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

    /**
 	* Constructor
 	*
 	* @param \phpbb\db\driver\driver_interface    $db                          Database object
    * @param ContainerInterface                	  $container       	           Service container interface
    * @param string                               $consim_action_table         Name of the table used to store data
 	* @access public
 	*/
 	public function __construct(\phpbb\db\driver\driver_interface $db,
                                ContainerInterface $container,
                                $consim_action_table)
 	{
        $this->db = $db;
        $this->container = $container;
        $this->consim_action_table = $consim_action_table;
 	}

    /**
	* Get all finished actions
	*
	* @return array Array of Action
	* @access public
	*/
	public function getAllFinishedActions()
	{
        $entities = array();

        $sql = 'SELECT id, user_id, type, time, status
            FROM ' . $this->consim_action_table . '
			WHERE time <= ' . time();
		$result = $this->db->sql_query($sql);

        while($row = $this->db->sql_fetchrow($result))
        {
            $entities[] = $this->container->get('consim.core.entity.Action')->import($row);
        }
        $this->db->sql_freeresult($result);

		return $entities;
    }

    /**
	* Get actions from user
	*
    * @param int $user_id User ID
	* @return array Array of Action
	* @access public
	*/
	public function getActionsFromUser($user_id)
	{
        $entities = array();

        $sql = 'SELECT id, user_id, type, time, status
            FROM ' . $this->consim_action_table . '
			WHERE user_id = ' . (int) $user_id;
		$result = $this->db->sql_query($sql);

        while($row = $this->db->sql_fetchrow($result))
        {
            $entities[] = $this->container->get('consim.core.entity.Action')->import($row);
        }
        $this->db->sql_freeresult($result);

		return $entities;
    }

}
