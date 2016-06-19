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
class Building extends abstractEntity
{
	/**
	* All of fields of this objects
	*
	**/
	protected static $fields = array(
		'id'			=> 'integer',
		'name'			=> 'string',
		'description'	=> 'string',
		'type_id'		=> 'integer',
		'type_name'		=> 'string',
	);

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned = array(
		'id',
	);

	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_building_table;
	protected $consim_building_type_table;

	/**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface	$db								Database object
	* @param string								$consim_building_table			Name of the table used to store data
	* @param string								$consim_building_type_table		Name of the table used to store data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db,
								$consim_building_table,
								$consim_building_type_table)
	{
		$this->db = $db;
		$this->consim_building_table = $consim_building_table;
		$this->consim_building_type_table = $consim_building_type_table;
	}

	/**
	* Load the data from the database for this object
	*
	* @param int $id user identifier
	* @return Building $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function load($id)
	{
		$sql = 'SELECT lb.id, lb.name, lb.description, b.id AS type_id, b.name AS type_name
			FROM ' . $this->consim_building_table . ' lb
			LEFT JOIN ' . $this->consim_building_type_table . ' b ON lb.type_id = b.id
			WHERE lb.id = ' . (int) $id;
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
	 * Find the data from the database for this object
	 *
	 * @param int $location_id
	 * @param int $building_type_id
	 * @return Building $this object for chaining calls; load()->set()->save()
	 * @access public
	 * @throws \consim\core\exception\out_of_bounds
	 */
	public function find($location_id, $building_type_id)
	{
		$sql = 'SELECT lb.id, lb.name, lb.description, b.id AS type_id, b.name AS type_name
			FROM ' . $this->consim_building_table . ' lb
			LEFT JOIN ' . $this->consim_building_type_table . ' b ON lb.type_id = b.id
			WHERE b.id = '. $building_type_id .'
				AND lb.location_id = '. $location_id;
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
	* Get Building ID
	*
	* @return int ID
	* @access public
	*/
	public function getId()
	{
		return $this->getInteger($this->data['id']);
	}

	/**
	* Get Building Name
	*
	* @return string building name
	* @access public
	*/
	public function getName()
	{
		return $this->getString($this->data['name']);
	}
	
	/**
	* Get Building Description
	*
	* @return string building description
	* @access public
	*/
	public function getDescription()
	{
		return $this->getString($this->data['description']);
	}

	/**
	 * Get Id of Building Type
	 *
	 * @return int Id of building type
	 * @access public
	 */
	public function getTypeId()
	{
		return $this->getInteger($this->data['type_id']);
	}

	/**
	* Get Name of Building Type
	*
	* @return string Name of building type
	* @access public
	*/
	public function getTypeName()
	{
		return $this->getString($this->data['type_name']);
	}
}
