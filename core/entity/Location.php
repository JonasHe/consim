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
class Location extends abstractEntity
{
	/**
	* All of fields of this objects
	*
	**/
	protected static $fields = array(
    	'id'						=> 'integer',
      	'name'                      => 'string',
        'description'               => 'string',
        'image'                     => 'string',
      	'type'						=> 'string',
      	'province'					=> 'string',
        'country'					=> 'string',
	);

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned = array(
      	'id',
	);

	protected $data;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_location_table;
    protected $consim_location_type_table;
    protected $consim_province_table;
    protected $consim_country_table;

    /**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface    $db                          Database object
    * @param ContainerInterface                	  $container       	           Service container interface
    * @param string                               $consim_location_table       Name of the table used to store data
    * @param string                               $consim_location_type_table  Name of the table used to store data
    * @param string                               $consim_province_table       Name of the table used to store data
    * @param string                               $consim_country_table        Name of the table used to store data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db,
                                $consim_location_table,
                                $consim_location_type_table,
                                $consim_province_table,
                                $consim_country_table)
	{
		$this->db = $db;
		$this->consim_location_table = $consim_location_table;
        $this->consim_location_type_table = $consim_location_type_table;
        $this->consim_province_table = $consim_province_table;
        $this->consim_country_table = $consim_country_table;
	}

	/**
	* Load the data from the database for this object
	*
	* @param int $id user identifier
	* @return object $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\user\exception\out_of_bounds
	*/
	public function load($id)
	{
		$sql = 'SELECT l.id, l.name, l.description, l.image, t.name AS type, p.name AS province, c.name AS country
			FROM ' . $this->consim_location_table . ' l
            LEFT JOIN ' . $this->consim_location_type_table . ' t ON l.type_id = t.id
            LEFT JOIN ' . $this->consim_province_table . ' p ON l.province_id = p.id
            LEFT JOIN ' . $this->consim_country_table . ' c ON p.country_id = c.id
			WHERE l.id = ' . (int) $id;
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
	* Get Name
	*
	* @return string Name
	* @access public
	*/
	public function getName()
	{
		return $this->getString($this->data['name']);
	}

    /**
	* Get Description
	*
	* @return string Description
	* @access public
	*/
	public function getDescription()
	{
		return $this->getString($this->data['description']);
	}

    /**
	* Get Image
	*
	* @return string Image
	* @access public
	*/
	public function getImage()
	{
		return $this->getString($this->data['image']);
	}

    /**
	* Get Name of Type
	*
	* @return string Type
	* @access public
	*/
	public function getType()
	{
		return $this->data['type'];
	}

    /**
	* Get Name of Province
	*
	* @return string Province
	* @access public
	*/
	public function getProvince()
	{
		return $this->data['province'];
	}

    /**
	* Get Name of Province
	*
	* @return string Province
	* @access public
	*/
	public function getCountry()
	{
		return $this->data['country'];
	}
}
