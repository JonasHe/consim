<?php
/**
* @package ConSim for phpBB3.1
*
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*/

namespace consim\core\entity;

use Symfony\Component\DependencyInjection\ContainerInterface;

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
      	'type'						=> 'integer',
      	'province'					=> 'integer',
	);

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned = array(
      	'id',
        'type',
        'province',
	);

	protected $data;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

    /** @var ContainerInterface */
	protected $container;

	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_location_table;
    protected $consim_location_type_table;
    protected $consim_province_table;

   /**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface    $db                     Database object
    * @param ContainerInterface                	$container       	Service container interface
	* @param string                               $consim_location_table  Name of the table used to store data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db,
                                ContainerInterface $container,
                                $consim_location_table,
                                $consim_location_type_table,
                                $consim_province_table)
	{
		$this->db = $db;
        $this->container = $container;
		$this->consim_location_table = $consim_location_table;
        $this->consim_location_type_table = $consim_location_type_table;
        $this->consim_province_table = $consim_province_table;
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
		$sql = 'SELECT l.id, l.name, t.id AS t_id, t.name AS t_name, p.id AS p_id, p.name AS p_name, p.country AS p_country
			FROM ' . $this->consim_location_table . ' l
            LEFT JOIN ' . $this->consim_location_type_table . ' t ON l.type = t.id
            LEFT JOIN ' . $this->consim_province_table . ' p ON l.province = p.id
			WHERE l.id = ' . (int) $id;
		$result = $this->db->sql_query($sql);
		$local_data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($local_data === false)
		{
			throw new \consim\core\exception\out_of_bounds('id');
		}

        //Declare LocationType
        $type = array(
            'id'        => $local_data['t_id'],
            'name'      => $local_data['t_name'],
        );
        $type = $this->container->get('consim.core.entity.LocationType')->import($type);

        //Declare Province
        $province = array(
            'id'        => $local_data['p_id'],
            'name'      => $local_data['p_name'],
            'country'   => $local_data['p_country'],
        );
        $province = $this->container->get('consim.core.entity.Province')->import($province);

        $this->data = array(
            'id'        => $local_data['id'],
            'name'      => $local_data['name'],
            'type'      => $type,
            'province'  => $province,
        );

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
	* Get Type
	*
	* @return object LocationType
	* @access public
	*/
	public function getType()
	{
		return $this->data['type'];
	}

    /**
	* Get Province
	*
	* @return object Province
	* @access public
	*/
	public function getProvince()
	{
		return $this->data['province'];
	}
}
