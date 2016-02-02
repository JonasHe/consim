<?php
/**
*
* @package ConSim for phpBB3.1
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace consim\core\entity;

/**
* Entity for a single consim user
*/
class consim_user
{
	/**
	* Data for this entity
	*
	* @var array
	*	user_id
	*	vorname
	*	nachname
	*	geschlecht
	*	geburtsland
	*	religion
	*	haarfarbe
	*	augenfarbe
	*	besondere_merkmale
	*	sprache_tadsowisch
	*	sprache_bakirisch
	*	sprache_suranisch
	*	rhetorik
	*	wirtschaft
	*	technik
	*	nahkampf
	*	militarkunde
	*	spionage
	*	uberlebenskunde
	* @access protected
	*/
	protected $data;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/**
	* The database table the consim user are stored in
	*
	* @var string
	*/
	protected $consim_user_table;

	/**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface    $db                 Database object
	* @param string                               $consim_user_table  Name of the table used to store consim user data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db, $consim_user_table)
	{
		$this->db = $db;
		$this->consim_user_table = $consim_user_table;
	}

	/**
	* Load the data from the database for this consim user
	*
	* @param int $user_id user identifier
	* @return consim_user $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function load($user_id)
	{
		$sql = 'SELECT *
			FROM ' . $this->consim_user_table . '
			WHERE user_id = ' . (int) $user_id;
		$result = $this->db->sql_query($sql);
		$this->data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($this->data === false)
		{
			// A consim user does not exist
			throw new \consim\core\exception\out_of_bounds('user_id');
		}

		return $this;
	}

	/**
	* Import data for this consim_user
	*
	* Used when the data is already loaded externally.
	* Any existing data on this consim user is over-written.
	* All data is validated and an exception is thrown if any data is invalid.
	*
	* @param array $data Data array, typically from the database
	* @return consim_user $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\base
	*/
	public function import($data)
	{
		// Clear out any saved data
		$this->data = array();

		// All of our fields
		$fields = array(
			// column							=> data type (see settype())
			'user_id'							=> 'integer',
			'vorname'							=> 'string',
			'nachname'							=> 'string',
			'geschlecht'						=> 'string',
			'geburtsland'						=> 'string',
			'religion'							=> 'string',
			'haarfarbe'							=> 'string',
			'augenfarbe'						=> 'string',
			'besondere_merkmale'				=> 'string',
			'sprache_tadsowisch'				=> 'integer',
			'sprache_bakirisch'				=> 'integer',
			'sprache_suranisch'				=> 'integer',
			'rhetorik'							=> 'integer',
			'wirtschaft'						=> 'integer',
			'technik'							=> 'integer',
			'nahkampf'							=> 'integer',
			'militarkunde'						=> 'integer',
			'spionage'							=> 'integer',
			'uberlebenskunde'					=> 'integer',
		);

		// Go through the basic fields and set them to our data array
		foreach ($fields as $field => $type)
		{
			// If the data wasn't sent to us, throw an exception
			if (!isset($data[$field]))
			{
				throw new \consim\core\exception\invalid_argument(array($field, 'FIELD_MISSING'));
			}

			// If the type is a method on this class, call it
			if (method_exists($this, $type))
			{
				$this->$type($data[$field]);
			}
			else
			{
				// settype passes values by reference
				$value = $data[$field];

				// We're using settype to enforce data types
				settype($value, $type);

				$this->data[$field] = $value;
			}
		}

		// Some fields must be unsigned (>= 0)
		$validate_unsigned = array(
			'user_id',
			'sprache_tadsowisch',
			'sprache_bakirisch',
			'sprache_suranisch',
			'rhetorik',
			'wirtschaft',
			'technik',
			'nahkampf',
			'militarkunde',
			'spionage',
			'uberlebenskunde',
		);

		foreach ($validate_unsigned as $field)
		{
			// If the data is less than 0, it's not unsigned and we'll throw an exception
			if ($this->data[$field] < 0)
			{
				throw new \consim\core\exception\out_of_bounds($field);
			}
		}

		return $this;
	}

	/**
	* Insert the consim user for the first time
	*
	* Will throw an exception if the consim user was already inserted (call save() instead)
	*
	* @return consim_user $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\user\exception\out_of_bounds
	*/
	public function insert()
	{
		if (!empty($this->data['user_id']))
		{
			// The consim user already exists
			throw new \consim\core\exception\out_of_bounds('user_id');
		}

		// Make extra sure there is no user_id set
		unset($this->data['user_id']);

		// Insert the consim user data to the database
		$sql = 'INSERT INTO ' . $this->consim_user_table . ' ' . $this->db->sql_build_array('INSERT', $this->data);
		$this->db->sql_query($sql);

		// Set the user_id using the id created by the SQL insert
		$this->data['user_id'] = (int) $this->db->sql_nextid();

		return $this;
	}

	/**
	* Save the current settings to the database
	*
	* This must be called before closing or any changes will not be saved!
	* If adding a consim user (saving for the first time), you must call insert() or an exeception will be thrown
	*
	* @return consim_user $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function save()
	{
		if (empty($this->data['user_id']))
		{
			// The consim user does not exist
			throw new \consim\core\exception\out_of_bounds('user_id');
		}

		$sql = 'UPDATE ' . $this->consim_user_table . '
			SET ' . $this->db->sql_build_array('UPDATE', $this->data) . '
			WHERE user_id = ' . $this->get_id();
		$this->db->sql_query($sql);

		return $this;
	}

	/**
	* Get id
	*
	* @return int User identifier
	* @access public
	*/
	public function getId()
	{
		return (isset($this->data['user_id'])) ? (int) $this->data['user_id'] : 0;
	}

	/**
	* Get vorname
	*
	* @return string vorname
	* @access public
	*/
	public function getVorname()
	{
		return (isset($this->data['vorname'])) ? (string) $this->data['vorname'] : '';
	}

	/**
	* Set vorname
	*
	* @param string $vorname
	* @return consim_user $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setVorname($vorname)
	{
		// Enforce a string
		$vorname = (string) $vorname;

		// We limit the title length to 255 characters
		if (truncate_string($vorname, 255) != $vorname)
		{
			throw new \consim\core\exception\unexpected_value(array('vorname', 'TOO_LONG'));
		}

		// Set the title on our data array
		$this->data['vorname'] = $vorname;

		return $this;
	}


}
