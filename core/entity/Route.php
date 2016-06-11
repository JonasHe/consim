<?php
/**
* @package ConSim for phpBB3.1
*
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*/

namespace consim\core\entity;

/**
* Entity for a single ressource
*/
class Route extends abstractEntity
{
	/**
	* All of fields of this objects
	*
	**/
	protected static $fields = array(
		'id'					=> 'integer',
		'start_location_id'		=> 'integer',
		'end_location_id'		=> 'integer',
		'time'					=> 'integer',
	);

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned = array(
		'id',
		'start_location_id',
		'end_location_id',
		'time',
	);
	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_route_table;

   /**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface	$db							Database object
	* @param string								$consim_location_type_table	Name of the table used to store data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db, $consim_route_table)
	{
		$this->db = $db;
		$this->consim_route_table = $consim_route_table;
	}

	/**
	* Load the data from the database for this object
	*
	* @param int $start Start Location
	* @param int $end End Location
	* @return Route $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function load($start, $end)
	{
		$sql = 'SELECT id, start_location_id, end_location_id, time
			FROM ' . $this->consim_route_table . '
			WHERE start_location_id = '. (int) $start .' AND end_location_id = '. (int) $end .'
			   OR start_location_id = '. (int) $end.' AND end_location_id = '. (int) $start;
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
	* Get User ID
	*
	* @return int ID
	* @access public
	*/
	public function getId()
	{
		return $this->getInteger($this->data['id']);
	}

	/**
	* Get Start Location ID
	*
	* @return int ID
	* @access public
	*/
	public function getStartLocationId()
	{
		return $this->getInteger($this->data['start_location_id']);
	}

	/**
	* Get End Location ID
	*
	* @return int ID
	* @access public
	*/
	public function getEndLocationId()
	{
		return $this->getInteger($this->data['end_location_id']);
	}

	/**
	* Get Time
	*
	* @return int ID
	* @access public
	*/
	public function getTime()
	{
		return $this->getInteger($this->data['time']);
	}
}
