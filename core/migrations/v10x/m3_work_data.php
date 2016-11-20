<?php

/**
 *
 * @package ConSim for phpBB3.1
 * @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
 * @copyright (c) 2016 Jonas Heitmann (kommodoree@googlemail.com)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace consim\core\migrations\v10x;

class m3_work_data extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\consim\core\migrations\v10x\m2_basic_data');
	}

	/**
	 * Add or update data in the database
	 *
	 * @return array Array of table data
	 * @access public
	 */
	public function update_data()
	{
		/** @var \phpbb\user $user */
		global $user;

		// Load the installation lang file
		$user->add_lang_ext('consim/core', 'consim_install');

		return array(
			array('custom', array(array($this, 'insert_building_types'))),
			array('custom', array(array($this, 'insert_buildings'))),
			array('custom', array(array($this, 'insert_works'))),
			array('custom', array(array($this, 'insert_work_outputs'))),
		);
	}

	public function insert_building_types()
	{
		/** @var \phpbb\user $user */
		global $user;

		$buildings = array(
			array('id' => 1, 'name' => $user->lang('BUILDING_TYPE_1')),
			array('id' => 2, 'name' => $user->lang('BUILDING_TYPE_2')),
			array('id' => 3, 'name' => $user->lang('BUILDING_TYPE_3')),
			//array('id' => 4, 'name' => $user->lang('BUILDING_TYPE_4')),
			//array('id' => 5, 'name' => $user->lang('BUILDING_TYPE_5')),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_building_types', $buildings);
	}

	public function insert_buildings()
	{
		/** @var \phpbb\user $user */
		global $user;

		$location_buildings = array(
			array('id'			=> 1,
				  'name'		=> $user->lang('BUILDING_1'),
				  'description'	=> $user->lang('BUILDING_1_EXPLAIN'),
				  'type_id'		=> 1,
				  'location_id'	=> 1
			),
			array('id'			=> 2,
				  'name'		=> $user->lang('BUILDING_2'),
				  'description'	=> $user->lang('BUILDING_2_EXPLAIN'),
				  'type_id'		=> 2,
				  'location_id'	=> 1
			),
			array('id'			=> 3,
				  'name'		=> $user->lang('BUILDING_3'),
				  'description'	=> $user->lang('BUILDING_3_EXPLAIN'),
				  'type_id'		=> 3,
				  'location_id'	=> 1
			),
			array('id'			=> 4,
				  'name'		=> $user->lang('BUILDING_1'),
				  'description'	=> $user->lang('BUILDING_1_EXPLAIN'),
				  'type_id'		=> 1,
				  'location_id'	=> 2
			),
			array('id'			=> 5,
				  'name'		=> $user->lang('BUILDING_2'),
				  'description'	=> $user->lang('BUILDING_2_EXPLAIN'),
				  'type_id'		=> 2,
				  'location_id'	=> 2
			),
			array('id'			=> 6,
				  'name'		=> $user->lang('BUILDING_3'),
				  'description'	=> $user->lang('BUILDING_3_EXPLAIN'),
				  'type_id'		=> 3,
				  'location_id'	=> 2
			),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_buildings', $location_buildings);
	}

	public function insert_works()
	{
		/** @var \phpbb\user $user */
		global $user;

		$works = array(
			array(
				'id'					=> 1,
				'name'					=> $user->lang('WORK_1'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 1,
				'condition_1_id'		=> 1,
				'condition_1_trials'	=> 10,
				'condition_1_value'		=> 25,
				'condition_2_id'		=> 4,
				'condition_2_trials'	=> 5,
				'condition_2_value'		=> 20,
				'condition_3_id'		=> 0,
				'condition_3_trials'	=> 0,
				'condition_3_value'		=> 0,
				'experience_points'		=> serialize(array(0,0,0,1,2)),
			),
			array(
				'id'					=> 2,
				'name'					=> $user->lang('WORK_2'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 1,
				'condition_1_id'		=> 1,
				'condition_1_trials'	=> 10,
				'condition_1_value'		=> 30,
				'condition_2_id'		=> 4,
				'condition_2_trials'	=> 5,
				'condition_2_value'		=> 25,
				'condition_3_id'		=> 0,
				'condition_3_trials'	=> 0,
				'condition_3_value'		=> 0,
				'experience_points'		=> serialize(array(0,0,1,2,3)),
			),
			array(
				'id'					=> 3,
				'name'					=> $user->lang('WORK_3'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 1,
				'condition_1_id'		=> 1,
				'condition_1_trials'	=> 8,
				'condition_1_value'		=> 35,
				'condition_2_id'		=> 4,
				'condition_2_trials'	=> 7,
				'condition_2_value'		=> 35,
				'condition_3_id'		=> 0,
				'condition_3_trials'	=> 0,
				'condition_3_value'		=> 0,
				'experience_points'		=> serialize(array(0,1,2,3,4)),
			),
			array(
				'id'					=> 4,
				'name'					=> $user->lang('WORK_4'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 1,
				'condition_1_id'		=> 1,
				'condition_1_trials'	=> 6,
				'condition_1_value'		=> 25,
				'condition_2_id'		=> 4,
				'condition_2_trials'	=> 6,
				'condition_2_value'		=> 30,
				'condition_3_id'		=> 18,
				'condition_3_trials'	=> 3,
				'condition_3_value'		=> 25,
				'experience_points'		=> serialize(array(0,0,1,2,3)),
			),
			array(
				'id'					=> 5,
				'name'					=> $user->lang('WORK_5'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 1,
				'condition_1_id'		=> 1,
				'condition_1_trials'	=> 5,
				'condition_1_value'		=> 30,
				'condition_2_id'		=> 4,
				'condition_2_trials'	=> 6,
				'condition_2_value'		=> 35,
				'condition_3_id'		=> 18,
				'condition_3_trials'	=> 4,
				'condition_3_value'		=> 30,
				'experience_points'		=> serialize(array(0,1,2,3,4)),
			),
			array(
				'id'					=> 6,
				'name'					=> $user->lang('WORK_6'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 1,
				'condition_1_id'		=> 1,
				'condition_1_trials'	=> 4,
				'condition_1_value'		=> 35,
				'condition_2_id'		=> 4,
				'condition_2_trials'	=> 6,
				'condition_2_value'		=> 40,
				'condition_3_id'		=> 18,
				'condition_3_trials'	=> 5,
				'condition_3_value'		=> 35,
				'experience_points'		=> serialize(array(1,2,3,4,5)),
			),
			array(
				'id'					=> 7,
				'name'					=> $user->lang('WORK_7'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 2,
				'condition_1_id'		=> 17,
				'condition_1_trials'	=> 15,
				'condition_1_value'		=> 20,
				'condition_2_id'		=> 0,
				'condition_2_trials'	=> 0,
				'condition_2_value'		=> 0,
				'condition_3_id'		=> 0,
				'condition_3_trials'	=> 0,
				'condition_3_value'		=> 0,
				'experience_points'		=> serialize(array(0,0,0,1,2)),
			),
			array(
				'id'					=> 8,
				'name'					=> $user->lang('WORK_8'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 2,
				'condition_1_id'		=> 17,
				'condition_1_trials'	=> 15,
				'condition_1_value'		=> 25,
				'condition_2_id'		=> 0,
				'condition_2_trials'	=> 0,
				'condition_2_value'		=> 0,
				'condition_3_id'		=> 0,
				'condition_3_trials'	=> 0,
				'condition_3_value'		=> 0,
				'experience_points'		=> serialize(array(0,0,1,2,3)),
			),
			array(
				'id'					=> 9,
				'name'					=> $user->lang('WORK_9'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 2,
				'condition_1_id'		=> 17,
				'condition_1_trials'	=> 10,
				'condition_1_value'		=> 30,
				'condition_2_id'		=> 6,
				'condition_2_trials'	=> 5,
				'condition_2_value'		=> 20,
				'condition_3_id'		=> 0,
				'condition_3_trials'	=> 0,
				'condition_3_value'		=> 0,
				'experience_points'		=> serialize(array(1,2,3,4,5)),
			),
			array(
				'id'					=> 10,
				'name'					=> $user->lang('WORK_10'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 2,
				'condition_1_id'		=> 17,
				'condition_1_trials'	=> 8,
				'condition_1_value'		=> 25,
				'condition_2_id'		=> 16,
				'condition_2_trials'	=> 5,
				'condition_2_value'		=> 15,
				'condition_3_id'		=> 15,
				'condition_3_trials'	=> 3,
				'condition_3_value'		=> 20,
				'experience_points'		=> serialize(array(1,2,3,4,5)),
			),
			array(
				'id'					=> 11,
				'name'					=> $user->lang('WORK_11'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 2,
				'condition_1_id'		=> 17,
				'condition_1_trials'	=> 7,
				'condition_1_value'		=> 30,
				'condition_2_id'		=> 16,
				'condition_2_trials'	=> 4,
				'condition_2_value'		=> 20,
				'condition_3_id'		=> 15,
				'condition_3_trials'	=> 5,
				'condition_3_value'		=> 25,
				'experience_points'		=> serialize(array(2,3,4,5,6)),
			),
			array(
				'id'					=> 12,
				'name'					=> $user->lang('WORK_12'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 2,
				'condition_1_id'		=> 17,
				'condition_1_trials'	=> 6,
				'condition_1_value'		=> 35,
				'condition_2_id'		=> 16,
				'condition_2_trials'	=> 3,
				'condition_2_value'		=> 25,
				'condition_3_id'		=> 15,
				'condition_3_trials'	=> 6,
				'condition_3_value'		=> 30,
				'experience_points'		=> serialize(array(3,4,5,6,7)),
			),
			array(
				'id'					=> 13,
				'name'					=> $user->lang('WORK_13'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 3,
				'condition_1_id'		=> 8,
				'condition_1_trials'	=> 12,
				'condition_1_value'		=> 20,
				'condition_2_id'		=> 11,
				'condition_2_trials'	=> 3,
				'condition_2_value'		=> 20,
				'condition_3_id'		=> 0,
				'condition_3_trials'	=> 0,
				'condition_3_value'		=> 0,
				'experience_points'		=> serialize(array(0,0,0,1,2)),
			),
			array(
				'id'					=> 14,
				'name'					=> $user->lang('WORK_14'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 3,
				'condition_1_id'		=> 8,
				'condition_1_trials'	=> 9,
				'condition_1_value'		=> 25,
				'condition_2_id'		=> 11,
				'condition_2_trials'	=> 6,
				'condition_2_value'		=> 25,
				'condition_3_id'		=> 0,
				'condition_3_trials'	=> 0,
				'condition_3_value'		=> 0,
				'experience_points'		=> serialize(array(0,0,1,2,3)),
			),
			array(
				'id'					=> 15,
				'name'					=> $user->lang('WORK_15'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 3,
				'condition_1_id'		=> 8,
				'condition_1_trials'	=> 6,
				'condition_1_value'		=> 30,
				'condition_2_id'		=> 11,
				'condition_2_trials'	=> 9,
				'condition_2_value'		=> 35,
				'condition_3_id'		=> 0,
				'condition_3_trials'	=> 0,
				'condition_3_value'		=> 0,
				'experience_points'		=> serialize(array(1,2,3,4,5)),
			),
			array(
				'id'					=> 16,
				'name'					=> $user->lang('WORK_16'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 3,
				'condition_1_id'		=> 8,
				'condition_1_trials'	=> 5,
				'condition_1_value'		=> 20,
				'condition_2_id'		=> 11,
				'condition_2_trials'	=> 5,
				'condition_2_value'		=> 25,
				'condition_3_id'		=> 20,
				'condition_3_trials'	=> 5,
				'condition_3_value'		=> 20,
				'experience_points'		=> serialize(array(0,1,2,3,4)),
			),
			array(
				'id'					=> 17,
				'name'					=> $user->lang('WORK_17'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 3,
				'condition_1_id'		=> 8,
				'condition_1_trials'	=> 3,
				'condition_1_value'		=> 25,
				'condition_2_id'		=> 6,
				'condition_2_trials'	=> 7,
				'condition_2_value'		=> 25,
				'condition_3_id'		=> 20,
				'condition_3_trials'	=> 5,
				'condition_3_value'		=> 30,
				'experience_points'		=> serialize(array(1,2,3,4,5)),
			),
			array(
				'id'					=> 18,
				'name'					=> $user->lang('WORK_18'),
				'description'			=> '',
				'duration'				=> 1,
				'building_type_id'		=> 3,
				'condition_1_id'		=> 11,
				'condition_1_trials'	=> 5,
				'condition_1_value'		=> 30,
				'condition_2_id'		=> 20,
				'condition_2_trials'	=> 5,
				'condition_2_value'		=> 35,
				'condition_3_id'		=> 23,
				'condition_3_trials'	=> 5,
				'condition_3_value'		=> 25,
				'experience_points'		=> serialize(array(3,4,5,6,7)),
			),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_works', $works);
	}

	public function insert_work_outputs()
	{
		$output = array(
			/**
			 * work id = 1
			 */
			array(
				'id'					=> 1,
				'work_id'				=> 1,
				'success_threshold'		=> 1,
				'asset_id'				=> 1,
				'asset_value'			=> 2,
			),
			array(
				'id'					=> 2,
				'work_id'				=> 1,
				'success_threshold'		=> 2,
				'asset_id'				=> 1,
				'asset_value'			=> 4,
			),
			array(
				'id'					=> 3,
				'work_id'				=> 1,
				'success_threshold'		=> 3,
				'asset_id'				=> 1,
				'asset_value'			=> 6,
			),
			array(
				'id'					=> 4,
				'work_id'				=> 1,
				'success_threshold'		=> 4,
				'asset_id'				=> 1,
				'asset_value'			=> 8,
			),
			array(
				'id'					=> 5,
				'work_id'				=> 1,
				'success_threshold'		=> 5,
				'asset_id'				=> 1,
				'asset_value'			=> 10,
			),
			/**
			 * work id = 2
			 */
			array(
				'id'					=> 6,
				'work_id'				=> 2,
				'success_threshold'		=> 1,
				'asset_id'				=> 1,
				'asset_value'			=> 4,
			),
			array(
				'id'					=> 7,
				'work_id'				=> 2,
				'success_threshold'		=> 2,
				'asset_id'				=> 1,
				'asset_value'			=> 6,
			),
			array(
				'id'					=> 8,
				'work_id'				=> 2,
				'success_threshold'		=> 3,
				'asset_id'				=> 1,
				'asset_value'			=> 8,
			),
			array(
				'id'					=> 9,
				'work_id'				=> 2,
				'success_threshold'		=> 4,
				'asset_id'				=> 1,
				'asset_value'			=> 10,
			),
			array(
				'id'					=> 10,
				'work_id'				=> 2,
				'success_threshold'		=> 5,
				'asset_id'				=> 1,
				'asset_value'			=> 12,
			),
			/**
			 * work id = 3
			 */
			array(
				'id'					=> 11,
				'work_id'				=> 3,
				'success_threshold'		=> 1,
				'asset_id'				=> 1,
				'asset_value'			=> 6,
			),
			array(
				'id'					=> 12,
				'work_id'				=> 3,
				'success_threshold'		=> 2,
				'asset_id'				=> 1,
				'asset_value'			=> 8,
			),
			array(
				'id'					=> 13,
				'work_id'				=> 3,
				'success_threshold'		=> 3,
				'asset_id'				=> 1,
				'asset_value'			=> 10,
			),
			array(
				'id'					=> 14,
				'work_id'				=> 3,
				'success_threshold'		=> 4,
				'asset_id'				=> 1,
				'asset_value'			=> 12,
			),
			array(
				'id'					=> 15,
				'work_id'				=> 3,
				'success_threshold'		=> 5,
				'asset_id'				=> 1,
				'asset_value'			=> 14,
			),
			/**
			 * work id = 4
			 */
			array(
				'id'					=> 16,
				'work_id'				=> 4,
				'success_threshold'		=> 1,
				'asset_id'				=> 1,
				'asset_value'			=> 4,
			),
			array(
				'id'					=> 17,
				'work_id'				=> 4,
				'success_threshold'		=> 2,
				'asset_id'				=> 1,
				'asset_value'			=> 6,
			),
			array(
				'id'					=> 18,
				'work_id'				=> 4,
				'success_threshold'		=> 3,
				'asset_id'				=> 1,
				'asset_value'			=> 8,
			),
			array(
				'id'					=> 19,
				'work_id'				=> 4,
				'success_threshold'		=> 4,
				'asset_id'				=> 1,
				'asset_value'			=> 10,
			),
			array(
				'id'					=> 20,
				'work_id'				=> 4,
				'success_threshold'		=> 5,
				'asset_id'				=> 1,
				'asset_value'			=> 12,
			),
			/**
			 * work id = 5
			 */
			array(
				'id'					=> 21,
				'work_id'				=> 5,
				'success_threshold'		=> 1,
				'asset_id'				=> 1,
				'asset_value'			=> 6,
			),
			array(
				'id'					=> 22,
				'work_id'				=> 5,
				'success_threshold'		=> 2,
				'asset_id'				=> 1,
				'asset_value'			=> 8,
			),
			array(
				'id'					=> 23,
				'work_id'				=> 5,
				'success_threshold'		=> 3,
				'asset_id'				=> 1,
				'asset_value'			=> 10,
			),
			array(
				'id'					=> 24,
				'work_id'				=> 5,
				'success_threshold'		=> 4,
				'asset_id'				=> 1,
				'asset_value'			=> 12,
			),
			array(
				'id'					=> 25,
				'work_id'				=> 5,
				'success_threshold'		=> 5,
				'asset_id'				=> 1,
				'asset_value'			=> 14,
			),
			/**
			 * work id = 6
			 */
			array(
				'id'					=> 26,
				'work_id'				=> 6,
				'success_threshold'		=> 1,
				'asset_id'				=> 1,
				'asset_value'			=> 8,
			),
			array(
				'id'					=> 27,
				'work_id'				=> 6,
				'success_threshold'		=> 2,
				'asset_id'				=> 1,
				'asset_value'			=> 10,
			),
			array(
				'id'					=> 28,
				'work_id'				=> 6,
				'success_threshold'		=> 3,
				'asset_id'				=> 1,
				'asset_value'			=> 12,
			),
			array(
				'id'					=> 29,
				'work_id'				=> 6,
				'success_threshold'		=> 4,
				'asset_id'				=> 1,
				'asset_value'			=> 14,
			),
			array(
				'id'					=> 30,
				'work_id'				=> 6,
				'success_threshold'		=> 5,
				'asset_id'				=> 1,
				'asset_value'			=> 16,
			),
			/**
			 * work id = 7
			 */
			array(
				'id'					=> 31,
				'work_id'				=> 7,
				'success_threshold'		=> 1,
				'asset_id'				=> 1,
				'asset_value'			=> 2,
			),
			array(
				'id'					=> 32,
				'work_id'				=> 7,
				'success_threshold'		=> 2,
				'asset_id'				=> 1,
				'asset_value'			=> 4,
			),
			array(
				'id'					=> 33,
				'work_id'				=> 7,
				'success_threshold'		=> 3,
				'asset_id'				=> 1,
				'asset_value'			=> 6,
			),
			array(
				'id'					=> 34,
				'work_id'				=> 7,
				'success_threshold'		=> 4,
				'asset_id'				=> 1,
				'asset_value'			=> 8,
			),
			array(
				'id'					=> 35,
				'work_id'				=> 7,
				'success_threshold'		=> 5,
				'asset_id'				=> 1,
				'asset_value'			=> 10,
			),
			/**
			 * work id = 8
			 */
			array(
				'id'					=> 36,
				'work_id'				=> 8,
				'success_threshold'		=> 1,
				'asset_id'				=> 1,
				'asset_value'			=> 4,
			),
			array(
				'id'					=> 37,
				'work_id'				=> 8,
				'success_threshold'		=> 2,
				'asset_id'				=> 1,
				'asset_value'			=> 6,
			),
			array(
				'id'					=> 38,
				'work_id'				=> 8,
				'success_threshold'		=> 3,
				'asset_id'				=> 1,
				'asset_value'			=> 8,
			),
			array(
				'id'					=> 39,
				'work_id'				=> 8,
				'success_threshold'		=> 4,
				'asset_id'				=> 1,
				'asset_value'			=> 10,
			),
			array(
				'id'					=> 40,
				'work_id'				=> 8,
				'success_threshold'		=> 5,
				'asset_id'				=> 1,
				'asset_value'			=> 12,
			),
			/**
			 * work id = 9
			 */
			array(
				'id'					=> 41,
				'work_id'				=> 9,
				'success_threshold'		=> 1,
				'asset_id'				=> 1,
				'asset_value'			=> 6,
			),
			array(
				'id'					=> 42,
				'work_id'				=> 9,
				'success_threshold'		=> 2,
				'asset_id'				=> 1,
				'asset_value'			=> 8,
			),
			array(
				'id'					=> 43,
				'work_id'				=> 9,
				'success_threshold'		=> 3,
				'asset_id'				=> 1,
				'asset_value'			=> 10,
			),
			array(
				'id'					=> 44,
				'work_id'				=> 9,
				'success_threshold'		=> 4,
				'asset_id'				=> 1,
				'asset_value'			=> 12,
			),
			array(
				'id'					=> 45,
				'work_id'				=> 9,
				'success_threshold'		=> 5,
				'asset_id'				=> 1,
				'asset_value'			=> 14,
			),
			/**
			 * work id = 10
			 */
			array(
				'id'					=> 46,
				'work_id'				=> 10,
				'success_threshold'		=> 1,
				'asset_id'				=> 1,
				'asset_value'			=> 6,
			),
			array(
				'id'					=> 47,
				'work_id'				=> 10,
				'success_threshold'		=> 2,
				'asset_id'				=> 1,
				'asset_value'			=> 8,
			),
			array(
				'id'					=> 48,
				'work_id'				=> 10,
				'success_threshold'		=> 3,
				'asset_id'				=> 1,
				'asset_value'			=> 10,
			),
			array(
				'id'					=> 49,
				'work_id'				=> 10,
				'success_threshold'		=> 4,
				'asset_id'				=> 1,
				'asset_value'			=> 12,
			),
			array(
				'id'					=> 50,
				'work_id'				=> 10,
				'success_threshold'		=> 5,
				'asset_id'				=> 1,
				'asset_value'			=> 14,
			),
			/**
			 * work id = 11
			 */
			array(
				'id'					=> 51,
				'work_id'				=> 11,
				'success_threshold'		=> 1,
				'asset_id'				=> 1,
				'asset_value'			=> 8,
			),
			array(
				'id'					=> 52,
				'work_id'				=> 11,
				'success_threshold'		=> 2,
				'asset_id'				=> 1,
				'asset_value'			=> 10,
			),
			array(
				'id'					=> 53,
				'work_id'				=> 11,
				'success_threshold'		=> 3,
				'asset_id'				=> 1,
				'asset_value'			=> 12,
			),
			array(
				'id'					=> 54,
				'work_id'				=> 11,
				'success_threshold'		=> 4,
				'asset_id'				=> 1,
				'asset_value'			=> 14,
			),
			array(
				'id'					=> 55,
				'work_id'				=> 11,
				'success_threshold'		=> 5,
				'asset_id'				=> 1,
				'asset_value'			=> 16,
			),
			/**
			 * work id = 12
			 */
			array(
				'id'					=> 56,
				'work_id'				=> 12,
				'success_threshold'		=> 1,
				'asset_id'				=> 1,
				'asset_value'			=> 10,
			),
			array(
				'id'					=> 57,
				'work_id'				=> 12,
				'success_threshold'		=> 2,
				'asset_id'				=> 1,
				'asset_value'			=> 12,
			),
			array(
				'id'					=> 58,
				'work_id'				=> 12,
				'success_threshold'		=> 3,
				'asset_id'				=> 1,
				'asset_value'			=> 14,
			),
			array(
				'id'					=> 59,
				'work_id'				=> 12,
				'success_threshold'		=> 4,
				'asset_id'				=> 1,
				'asset_value'			=> 16,
			),
			array(
				'id'					=> 60,
				'work_id'				=> 12,
				'success_threshold'		=> 5,
				'asset_id'				=> 1,
				'asset_value'			=> 18,
			),
			/**
			 * work id = 13
			 */
			array(
				'id'					=> 61,
				'work_id'				=> 13,
				'success_threshold'		=> 1,
				'asset_id'				=> 1,
				'asset_value'			=> 2,
			),
			array(
				'id'					=> 62,
				'work_id'				=> 13,
				'success_threshold'		=> 2,
				'asset_id'				=> 1,
				'asset_value'			=> 4,
			),
			array(
				'id'					=> 63,
				'work_id'				=> 13,
				'success_threshold'		=> 3,
				'asset_id'				=> 1,
				'asset_value'			=> 6,
			),
			array(
				'id'					=> 64,
				'work_id'				=> 13,
				'success_threshold'		=> 4,
				'asset_id'				=> 1,
				'asset_value'			=> 8,
			),
			array(
				'id'					=> 65,
				'work_id'				=> 13,
				'success_threshold'		=> 5,
				'asset_id'				=> 1,
				'asset_value'			=> 10,
			),
			/**
			 * work id = 14
			 */
			array(
				'id'					=> 66,
				'work_id'				=> 14,
				'success_threshold'		=> 1,
				'asset_id'				=> 1,
				'asset_value'			=> 4,
			),
			array(
				'id'					=> 67,
				'work_id'				=> 14,
				'success_threshold'		=> 2,
				'asset_id'				=> 1,
				'asset_value'			=> 6,
			),
			array(
				'id'					=> 68,
				'work_id'				=> 14,
				'success_threshold'		=> 3,
				'asset_id'				=> 1,
				'asset_value'			=> 8,
			),
			array(
				'id'					=> 69,
				'work_id'				=> 14,
				'success_threshold'		=> 4,
				'asset_id'				=> 1,
				'asset_value'			=> 10,
			),
			array(
				'id'					=> 70,
				'work_id'				=> 14,
				'success_threshold'		=> 5,
				'asset_id'				=> 1,
				'asset_value'			=> 12,
			),
			/**
			 * work id = 15
			 */
			array(
				'id'					=> 71,
				'work_id'				=> 15,
				'success_threshold'		=> 1,
				'asset_id'				=> 1,
				'asset_value'			=> 8,
			),
			array(
				'id'					=> 72,
				'work_id'				=> 15,
				'success_threshold'		=> 2,
				'asset_id'				=> 1,
				'asset_value'			=> 10,
			),
			array(
				'id'					=> 73,
				'work_id'				=> 15,
				'success_threshold'		=> 3,
				'asset_id'				=> 1,
				'asset_value'			=> 12,
			),
			array(
				'id'					=> 74,
				'work_id'				=> 15,
				'success_threshold'		=> 4,
				'asset_id'				=> 1,
				'asset_value'			=> 14,
			),
			array(
				'id'					=> 75,
				'work_id'				=> 15,
				'success_threshold'		=> 5,
				'asset_id'				=> 1,
				'asset_value'			=> 16,
			),
			/**
			 * work id = 16
			 */
			array(
				'id'					=> 76,
				'work_id'				=> 16,
				'success_threshold'		=> 1,
				'asset_id'				=> 1,
				'asset_value'			=> 4,
			),
			array(
				'id'					=> 77,
				'work_id'				=> 16,
				'success_threshold'		=> 2,
				'asset_id'				=> 1,
				'asset_value'			=> 6,
			),
			array(
				'id'					=> 78,
				'work_id'				=> 16,
				'success_threshold'		=> 3,
				'asset_id'				=> 1,
				'asset_value'			=> 8,
			),
			array(
				'id'					=> 79,
				'work_id'				=> 16,
				'success_threshold'		=> 4,
				'asset_id'				=> 1,
				'asset_value'			=> 10,
			),
			array(
				'id'					=> 80,
				'work_id'				=> 16,
				'success_threshold'		=> 5,
				'asset_id'				=> 1,
				'asset_value'			=> 12,
			),
			/**
			 * work id = 17
			 */
			array(
				'id'					=> 81,
				'work_id'				=> 17,
				'success_threshold'		=> 1,
				'asset_id'				=> 1,
				'asset_value'			=> 6,
			),
			array(
				'id'					=> 82,
				'work_id'				=> 17,
				'success_threshold'		=> 2,
				'asset_id'				=> 1,
				'asset_value'			=> 8,
			),
			array(
				'id'					=> 83,
				'work_id'				=> 17,
				'success_threshold'		=> 3,
				'asset_id'				=> 1,
				'asset_value'			=> 10,
			),
			array(
				'id'					=> 84,
				'work_id'				=> 17,
				'success_threshold'		=> 4,
				'asset_id'				=> 1,
				'asset_value'			=> 12,
			),
			array(
				'id'					=> 85,
				'work_id'				=> 17,
				'success_threshold'		=> 5,
				'asset_id'				=> 1,
				'asset_value'			=> 14,
			),
			/**
			 * work id = 18
			 */
			array(
				'id'					=> 86,
				'work_id'				=> 18,
				'success_threshold'		=> 1,
				'asset_id'				=> 1,
				'asset_value'			=> 10,
			),
			array(
				'id'					=> 87,
				'work_id'				=> 18,
				'success_threshold'		=> 2,
				'asset_id'				=> 1,
				'asset_value'			=> 12,
			),
			array(
				'id'					=> 88,
				'work_id'				=> 18,
				'success_threshold'		=> 3,
				'asset_id'				=> 1,
				'asset_value'			=> 14,
			),
			array(
				'id'					=> 89,
				'work_id'				=> 18,
				'success_threshold'		=> 4,
				'asset_id'				=> 1,
				'asset_value'			=> 16,
			),
			array(
				'id'					=> 90,
				'work_id'				=> 18,
				'success_threshold'		=> 5,
				'asset_id'				=> 1,
				'asset_value'			=> 18,
			),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'consim_work_outputs', $output);
	}

}
