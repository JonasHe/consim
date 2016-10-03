<?php
/**
 * @package ConSim for phpBB3.1
 *
 * @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace consim\core\service;

use consim\core\entity\WorkOutput;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Operator for Inventory
 */
class WorkService
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var ContainerInterface */
	protected $container;

	/** @var \phpbb\template\template */
	protected $template;

	/**
	 * The database table the consim user data are stored in
	 * @var string
	 */
	protected $consim_work_table;
	protected $consim_work_output_table;
	protected $consim_skill_table;
	protected $consim_item_table;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface		$db							Database object
	 * @param ContainerInterface					$container					Service container interface
	 * @param \phpbb\template\template				$template					Template object
	 * @param string								$consim_work_table			Name of the table used to store data
	 * @param string								$consim_work_output_table	Name of the table used to store data
	 * @param string								$consim_skill_table			Name of the table used to store data
	 * @param string								$consim_item_table			Name of the table used to store data
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db,
		ContainerInterface $container,
		\phpbb\template\template $template,
		$consim_work_table,
		$consim_work_output_table,
		$consim_skill_table,
		$consim_item_table)
	{
		$this->db = $db;
		$this->container = $container;
		$this->template = $template;
		$this->consim_work_table = $consim_work_table;
		$this->consim_work_output_table = $consim_work_output_table;
		$this->consim_skill_table = $consim_skill_table;
		$this->consim_item_table = $consim_item_table;
	}

	/**
	 * Get all works of building type
	 *
	 * @param int $buildingType
	 * @return \consim\core\entity\Work[]
	 * @access public
	 */
	public function getWorks($buildingType)
	{
		$entities = array();

		$sql = 'SELECT w.id, w.name, w.description, w.duration, w.building_type_id, 
				w.condition_1_id, w.condition_1_trials, w.condition_1_value, COALESCE(s1.name,"") AS condition_1_name,
				w.condition_2_id, w.condition_2_trials, w.condition_2_value, COALESCE(s2.name,"") AS condition_2_name,
				w.condition_3_id, w.condition_3_trials, w.condition_3_value, COALESCE(s3.name,"") AS condition_3_name,
				w.experience_points
			FROM ' . $this->consim_work_table . ' w
			LEFT JOIN '. $this->consim_skill_table .' s1 ON s1.id = w.condition_1_id
			LEFT JOIN '. $this->consim_skill_table .' s2 ON s2.id = w.condition_2_id
			LEFT JOIN '. $this->consim_skill_table .' s3 ON s3.id = w.condition_3_id
			WHERE w.building_type_id = '.  $buildingType;
		$result = $this->db->sql_query($sql);

		while($row = $this->db->sql_fetchrow($result))
		{
			$entities[] = $this->container->get('consim.core.entity.work')->import($row);
		}
		$this->db->sql_freeresult($result);

		return $entities;
	}

	/**
	 * Get all Output for a Work
	 *
	 * @param int $work_id
	 * @return WorkOutput[]
	 */
	public function getOutputs($work_id)
	{
		$outputs = array();

		$sql = 'SELECT o.id, o.work_id, o.successful_trials,
				o.output_id, o.output_value, COALESCE(i.name,"") AS output_name, o.success_threshold
			FROM ' . $this->consim_work_output_table . ' o
			LEFT JOIN '. $this->consim_item_table .' i ON i.id = o.output_id
			WHERE o.work_id = '.  $work_id;
		$result = $this->db->sql_query($sql);

		while($row = $this->db->sql_fetchrow($result))
		{
			$outputs[] = $this->container->get('consim.core.entity.work_output')->import($row);
		}
		$this->db->sql_freeresult($result);

		return $outputs;
	}

	/**
	 * Get all Output for a Work
	 * which sorted by output_id
	 *
	 * @param int $work_id
	 * @return WorkOutput[][];
	 * 		WorkOutput[output_id]['name'] = output_name
	 * 		WorkOutput[output_id][success_threshold] = work_output
	 */
	public function getSortedOutputs($work_id)
	{
		$outputs = array();

		$sql = 'SELECT o.id, o.work_id, o.success_threshold,
				o.output_id, o.output_value, COALESCE(i.name,"") AS output_name, o.success_threshold
			FROM ' . $this->consim_work_output_table . ' o
			LEFT JOIN '. $this->consim_item_table .' i ON i.id = o.output_id
			WHERE o.work_id = '.  $work_id .'
			ORDER BY o.id, o.success_threshold ASC';
		$result = $this->db->sql_query($sql);

		while($row = $this->db->sql_fetchrow($result))
		{
			$outputs[$row['output_id']]['name'] = $row['output_name'];
			$outputs[$row['output_id']][] = $this->container->get('consim.core.entity.work_output')->import($row);
		}
		$this->db->sql_freeresult($result);

		return $outputs;
	}

	public function allWorksToTemplate($buildingTypeId)
	{
		$userService = $this->container->get('consim.core.service.user');
		$currentUser = $userService->getCurrentUser();
		$userSkillService = $this->container->get('consim.core.service.user_skill');
		$currentUserSkill = $userSkillService->getCurrentUserSkills();
		$works = $this->getWorks($buildingTypeId);
		foreach ($works as $work)
		{
			$s_hidden_fields = build_hidden_fields(array(
				'work_id'		=> $work->getId(),
			));

			$can_work = true;
			if($currentUser->getActive() ||
				($work->getCondition1Id() > 0 && $currentUserSkill[$work->getCondition1Id()]->getValue() < $work->getCondition1Value()) ||
				($work->getCondition2Id() > 0 && $currentUserSkill[$work->getCondition2Id()]->getValue() < $work->getCondition2Value()) ||
				($work->getCondition3Id() > 0 && $currentUserSkill[$work->getCondition3Id()]->getValue() < $work->getCondition3Value())
			)
			{
				$can_work = false;
			}

			$this->template->assign_block_vars('works', array(
				'NAME'					=> $work->getName(),
				'DURATION'				=> date("i:s", $work->getDuration()),
				'CONDITION_1_TYPE'		=> $work->getCondition1Name(),
				'CONDITION_1_TRIALS'	=> $work->getCondition1Trials(),
				'CONDITION_1_VALUE'		=> $work->getCondition1Value(),
				'CONDITION_2_TYPE'		=> $work->getCondition2Name(),
				'CONDITION_2_TRIALS'	=> $work->getCondition2Trials(),
				'CONDITION_2_VALUE'		=> $work->getCondition2Value(),
				'CONDITION_3_TYPE'		=> $work->getCondition3Name(),
				'CONDITION_3_TRIALS'	=> $work->getCondition3Trials(),
				'CONDITION_3_VALUE'		=> $work->getCondition3Value(),
				'EXPERIENCE_POINTS'		=> implode("/", $work->getExperiencePoints()),
				'CAN_WORK'				=> $can_work,
				'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
			));

			foreach ($work->getSortedOutputs() as $type => $outputs)
			{
				$this->template->assign_block_vars('works.outputs', array(
					'TYPE'			=> $outputs['name'],
				));

				/** @var WorkOutput[] $outputs */
				for($i=0; $i < 5; $i++)
				{
					$this->template->assign_block_vars('works.outputs.types', array(
						'VALUE'			=> (isset($outputs[$i]))? $outputs[$i]->getOutputValue() : 0,
					));
				}
			}
		}
	}
}
