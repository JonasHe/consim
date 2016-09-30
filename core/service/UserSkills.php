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
class UserSkills
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var ContainerInterface */
	protected $container;

	/**
	 * The database table the consim user data are stored in
	 * @var string
	 */
	protected $consim_skill_table;
	protected $consim_user_skill_table;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface		$db							Database object
	 * @param ContainerInterface					$container					Service container interface
	 * @param string								$consim_skill_table			Name of the table used to store data
	 * @param string								$consim_user_skill_table	Name of the table used to store data
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db,
								ContainerInterface $container,
								$consim_skill_table,
								$consim_user_skill_table)
	{
		$this->db = $db;
		$this->container = $container;
		$this->consim_skill_table = $consim_skill_table;
		$this->consim_user_skill_table = $consim_user_skill_table;
	}

	/**
	 * Get all Skills
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
	 * Get all Skills, which sorted by Categories
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
	 * Get all Skills
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