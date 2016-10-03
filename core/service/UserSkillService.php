<?php
/**
 * @package ConSim for phpBB3.1
 *
 * @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace consim\core\service;

use consim\core\entity\Skill;
use consim\core\entity\UserSkill;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Operator for Inventory
 */
class UserSkillService
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var ContainerInterface */
	protected $container;

	/** @var \consim\core\service\UserService */
	protected $userService;

	/**
	 * The database table the consim user data are stored in
	 * @var string
	 */
	protected $consim_skill_table;
	protected $consim_user_skill_table;

	/** @var  \consim\core\entity\UserSkill[]|null */
	private $currentUserSkills = null;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface		$db							Database object
	 * @param ContainerInterface					$container					Service container interface
	 * @param \consim\core\service\UserService		$userService				UserService object
	 * @param string								$consim_skill_table			Name of the table used to store data
	 * @param string								$consim_user_skill_table	Name of the table used to store data
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db,
		ContainerInterface $container,
		\consim\core\service\UserService $userService,
		$consim_skill_table,
		$consim_user_skill_table)
	{
		$this->db = $db;
		$this->container = $container;
		$this->userService = $userService;
		$this->consim_skill_table = $consim_skill_table;
		$this->consim_user_skill_table = $consim_user_skill_table;
	}

	/**
	 * Return the skills of current user
	 *
	 * @return \consim\core\entity\UserSkill[]|null
	 */
	public function getCurrentUserSkills()
	{
		if($this->currentUserSkills == null)
		{
			$this->currentUserSkills = $this->getUserSkills($this->userService->getCurrentUser()->getUserId());
		}

		return $this->currentUserSkills;
	}

	/**
	 * Get all skills
	 *
	 * @return Skill[]
	 */
	public function getSkills()
	{
		$skills = array();

		$sql = 'SELECT id, name, cat, country_id
			FROM ' . $this->consim_skill_table;
		$result = $this->db->sql_query($sql);

		while($row = $this->db->sql_fetchrow($result))
		{
			$skills[] = $this->container->get('consim.core.entity.consim_skill')->import($row);
		}
		$this->db->sql_freeresult($result);

		return $skills;
	}

	/**
	 * Get all skills, which sorted by categories
	 *
	 * @param $skills Skill[]|UserSkill[]
	 * @return Skill[][]|UserSkill[][]
	 */
	public function sortSkillsByCategory($skills)
	{
		$sortedSkills = array();

		foreach($skills as $skill)
		{
			$sortedSkills[$skill->getCategory()][] = $skill;
		}

		return $sortedSkills;
	}

	/**
	 * Get all skills of user
	 *
	 * @return UserSkill[]
	 */
	public function getUserSkills($user_id)
	{
		$skills = array();

		$sql = 'SELECT us.id, us.user_id, us.skill_id, s.name, s.cat, us.value
			FROM ' . $this->consim_user_skill_table .' us
			LEFT JOIN '. $this->consim_skill_table .' s ON s.id = us.skill_id
			WHERE us.user_id = '. $user_id;
		$result = $this->db->sql_query($sql);

		while($row = $this->db->sql_fetchrow($result))
		{
			$skills[$row['skill_id']] = $this->container->get('consim.core.entity.consim_user_skill')->import($row);
		}
		$this->db->sql_freeresult($result);

		return $skills;
	}
}
