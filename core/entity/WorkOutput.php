<?php
/**
 * @package ConSim for phpBB3.1
 *
 * @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace consim\core\entity;

/**
 * Entity
 */
class WorkOutput extends abstractEntity
{
	/**
	 * All of fields of this objects
	 *
	 **/
	protected static $fields = array(
		'id'					=> 'integer',
		'work_id'				=> 'integer',
		'success_threshold'		=> 'integer',
		'output_id' 			=> 'integer',
		'output_value'			=> 'integer',
		'output_name'			=> 'string',
	);

	/**
	 * Some fields must be unsigned (>= 0)
	 **/
	protected static $validate_unsigned = array(
		'id',
		'work_id',
		'success_threshold',
		'output_id',
		'output_value',
	);
	/**
	 * The database table the consim user data are stored in
	 * @var string
	 */
	protected $consim_work_output_table;
	protected $consim_item_table;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface	$db							Database object
	 * @param string							$consim_work_output_table	Name of the table used to store data
	 * @param string							$consim_item_table			Name of the table used to store data
	 * @access public
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db,
		$consim_work_output_table,
		$consim_item_table)
	{
		$this->db = $db;
		$this->consim_work_output_table = $consim_work_output_table;
		$this->consim_item_table = $consim_item_table;
	}

	/**
	 * Load the data from the database for this object
	 *
	 * @param int $id WorkOutput id
	 * @return WorkOutput $this object for chaining calls; load()->set()->save()
	 * @access public
	 * @throws \consim\core\exception\out_of_bounds
	 */
	public function load($id)
	{
		$sql = 'SELECT o.id, o.work_id, o.success_threshold,
				o.output_id, o.output_value, COALESCE(i.name,"") AS output_name
			FROM ' . $this->consim_work_output_table . ' o
			LEFT JOIN '. $this->consim_item_table .' i ON i.id = o.output_id
			WHERE w.id = '.  $id;
		$result = $this->db->sql_query($sql);
		$this->data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($this->data === false)
		{
			throw new \consim\core\exception\out_of_bounds('id');
		}

		return $this;
	}

	/**
	 * Get ID
	 *
	 * @return int ID
	 * @access public
	 */
	public function getId()
	{
		return $this->getInteger($this->data['id']);
	}

	/**
	 * Get Duration
	 *
	 * @return int Duration
	 * @access public
	 */
	public function getWorkId()
	{
		return $this->getString($this->data['work_id']);
	}

	/**
	 * Get successful trials
	 *
	 * @return int Successful Trials
	 * @access public
	 */
	public function getSuccessThreshold()
	{
		return $this->getInteger($this->data['success_threshold']);
	}

	/**
	 * Get Output ID
	 *
	 * @return int Output ID
	 * @access public
	 */
	public function getOutputId()
	{
		return $this->getInteger($this->data['output_id']);
	}

	/**
	 * Get Output value
	 *
	 * @return int Output value
	 * @access public
	 */
	public function getOutputValue()
	{
		return $this->getInteger($this->data['output_value']);
	}

	/**
	 * Get Output Name
	 *
	 * @return string Output Name
	 * @access public
	 */
	public function getOutputName()
	{
		return $this->getString($this->data['output_name']);
	}
}
