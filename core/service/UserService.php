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
class UserService
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var ContainerInterface */
	protected $container;

	/** @var \phpbb\user */
	protected $user;

	/** @var \consim\core\entity\ConsimUser|null */
	protected $currentUser = null;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface		$db				Database object
	 * @param ContainerInterface					$container		Service container interface
	 * @param \phpbb\user							$user			User object
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db,
		ContainerInterface $container,
		\phpbb\user $user)
	{
		$this->db = $db;
		$this->container = $container;
		$this->user = $user;
	}

	/**
	 * @return \consim\core\entity\ConsimUser
	 */
	public function getCurrentUser()
	{
		if(null === $this->currentUser)
		{
			$this->currentUser = $this->getUser($this->user->data['user_id']);
		}

		return $this->currentUser;
	}

	/**
	 * @param $userId
	 *
	 * @return \consim\core\entity\ConsimUser
	 */
	public function getUser($userId)
	{
		if(null !== $this->currentUser && $this->currentUser->getUserId())
		{
			return $this->currentUser;
		}

		return $this->container->get('consim.core.entity.consim_user')->load($userId);
	}
}
