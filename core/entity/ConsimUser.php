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
class ConsimUser extends abstractEntity
{
	/**
	* All of fields of this objects
	*
	**/
	protected static $fields = array(
		'user_id'						=> 'integer',
		'vorname'						=> 'string',
		'nachname'						=> 'string',
		'geschlecht_id'					=> 'integer',
		'geburtsland_id'				=> 'integer',
		'religion_id'					=> 'integer',
		'haarfarbe_id'					=> 'integer',
		'augenfarbe_id'					=> 'integer',
		'besondere_merkmale_id'			=> 'integer',
		'experience_points'				=> 'integer',
		'location_id'					=> 'integer',
		'active'						=> 'bool'
	);

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned = array(
		'user_id',
		'geschlecht_id',
		'geburtsland_id',
		'religion_id',
		'haarfarbe_id',
		'augenfarbe_id',
		'besondere_merkmale_id',
		'experience_points',
		'location_id',
		'active',
	);

	/** @var ContainerInterface */
	protected $container;

	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_user_table;
	protected $consim_person_table;

	/**
	* Variable to save the data from person table
	* @var ConsimFigure[] $figure_data
	*/
	private $figure_data;

   /**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface	$db						Database object
	* @param ContainerInterface					$container				Service container interface
	* @param string								$consim_user_table		Name of the table used to store consim user
	* @param string								$consim_person_table	Name of the table used to store consim person data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db, ContainerInterface $container, $consim_user_table, $consim_person_table)
	{
		$this->db = $db;
		$this->container = $container;
		$this->consim_user_table = $consim_user_table;
		$this->consim_person_table = $consim_person_table;

		//get all figure data
		$this->figure_data = $this->figure_load();
	}

	/**
	* Load the data from the database for this ressource
	*
	* @param int $user_id user identifier
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
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
			throw new \consim\core\exception\out_of_bounds('user_id');
		}

		return $this;
	}

	/**
	* Load all data from the database from person table
	*
	* @return ConsimFigure[]
	* @access private
	*/
	private function figure_load()
	{
		$figure_data = array();

		$sql = 'SELECT id, groups, value, name
			FROM ' . $this->consim_person_table;
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$figure_data[$row['id']] = $this->container->get('consim.core.entity.consim_figure')
				->import($row);
		}
		$this->db->sql_freeresult($result);

		return $figure_data;
	}

	/**
	* Insert the Data for the first time
	*
	* Will throw an exception if the data was already inserted (call save() instead)
	*
	* @param int $user_id
	* @return object $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function insert($user_id)
	{
		//user_id muss bekannt sein
		if (empty($user_id) || $user_id < 0)
		{
			// The data already exists
			throw new \consim\core\exception\out_of_bounds('user_id');
		}

		$this->data['user_id'] = $user_id;

		// Insert the data to the database
		$sql = 'INSERT INTO ' . $this->consim_user_table . ' ' . $this->db->sql_build_array('INSERT', $this->data);
		$this->db->sql_query($sql);

		return $this;
	}

	/**
	* Save the current settings to the database
	*
	* This must be called before closing or any changes will not be saved!
	* If adding a data (saving for the first time), you must call insert() or an exeception will be thrown
	*
	* @return object $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function save()
	{
		if (empty($this->data['user_id']))
		{
			// The data does not exist
			throw new \consim\core\exception\out_of_bounds('user_id');
		}

		$sql = 'UPDATE ' . $this->consim_user_table . '
			SET ' . $this->db->sql_build_array('UPDATE', $this->data) . '
			WHERE user_id = ' . $this->getUserId();
		$this->db->sql_query($sql);

		return $this;
	}

	/**
	* Get User ID
	*
	* @return int User ID
	* @access public
	*/
	public function getUserId()
	{
		return $this->getString($this->data['user_id']);
	}

	/**
	* Get vorname
	*
	* @return string Vorname
	* @access public
	*/
	public function getVorname()
	{
		return $this->getString($this->data['vorname']);
	}

	/**
	* Set vorname
	*
	* @param string $vorname
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setVorname($vorname)
	{
		return $this->setString('vorname', $vorname, 255, 2);
	}

   /**
	* Get Nachname
	*
	* @return string nachname
	* @access public
	*/
	public function getNachname()
	{
		return $this->getString($this->data['nachname']);
	}
	/**
	* Set nachname
	*
	* @param string $nachname
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setNachname($nachname)
	{
		return $this->setString('nachname', $nachname, 255, 2);
	}

   /**
	* Get geschlecht
	*
	* @return ConsimFigure
	* @access public
	*/
	public function getGeschlecht()
	{
		//return $this->getString($this->data['geschlecht']);
		return $this->figure_data[$this->data['geschlecht_id']];
	}
	/**
	* Set geschlecht
	*
	* @param string $geschlecht
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setGeschlecht($geschlecht)
	{
		return $this->setFigure('geschlecht', $geschlecht);
	}

   /**
	* Get geburtsland
	*
	* @return ConsimFigure
	* @access public
	*/
	public function getGeburtsland()
	{
		return $this->figure_data[$this->data['geburtsland_id']];
	}
	/**
	* Set geburtsland
	*
	* @param string $geburtsland
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setGeburtsland($geburtsland)
	{
		return $this->setFigure('geburtsland', $geburtsland);
	}

   /**
	* Get religion
	*
	* @return ConsimFigure
	* @access public
	*/
	public function getReligion()
	{
		//return $this->getString($this->data['religion']);
		return $this->figure_data[$this->data['religion_id']];
	}
	/**
	* Set religion
	*
	* @param string $religion
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setReligion($religion)
	{
		return $this->setFigure('religion', $religion);
	}

   /**
	* Get haarfarbe
	*
	* @return ConsimFigure
	* @access public
	*/
	public function getHaarfarbe()
	{
		//return $this->getString($this->data['haarfarbe']);
		return $this->figure_data[$this->data['religion_id']];
	}
	/**
	* Set haarfarbe
	*
	* @param string $haarfarbe
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setHaarfarbe($haarfarbe)
	{
		return $this->setFigure('haarfarbe', $haarfarbe);
	}

   /**
	* Get augenfarbe
	*
	* @return ConsimFigure
	* @access public
	*/
	public function getAugenfarbe()
	{
		//return $this->getString($this->data['augenfarbe']);
		return $this->figure_data[$this->data['augenfarbe_id']];
	}
	/**
	* Set augenfarbe
	*
	* @param string $augenfarbe
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setAugenfarbe($augenfarbe)
	{
		return $this->setFigure('augenfarbe', $augenfarbe);
	}

   /**
	* Get Besondere Merkmale
	*
	* @return ConsimFigure
	* @access public
	*/
	public function getBesondereMerkmale()
	{
		//return $this->getString($this->data['besondere_merkmale']);
		return $this->figure_data[$this->data['besondere_merkmale_id']];
	}
	/**
	* Set Besondere Merkmale
	*
	* @param string $besondere_merkmale
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setBesondereMerkmale($besondere_merkmale)
	{
		return $this->setFigure('besondere_merkmale', $besondere_merkmale);
	}

	/**
	 * Get experience points
	 *
	 * @return int experience points
	 * @access public
	 */
	public function getExperiencePoints()
	{
		return $this->getInteger($this->data['experience_points']);
	}

	/**
	 * Set experience points
	 *
	 * @param int $experience_points
	 * @return ConsimUser
	 * @throws \consim\core\exception\out_of_bounds
	 */
	public function setExperiencePoints($experience_points)
	{
		return $this->setInteger('experience_points', $experience_points);
	}

	/**
   * Get location
   *
   * @return int location_id
   * @access public
   */
   public function getLocationId()
   {
	   return $this->getInteger($this->data['location_id']);
   }
   /**
   * Set location
   * Check if the new Location valid!
   *
   * @param int $location_id
   * @return ConsimUser $this object for chaining calls; load()->set()->save()
   * @access public
   * @throws \consim\core\exception\invalid_argument
   */
   public function setLocation($location_id)
   {
	   $sql = 'SELECT id
		   FROM phpbb_consim_routes
		   WHERE (start_location_id = '. (int) $location_id .' AND end_location_id = '. $this->data['location_id'] .')
				 OR (start_location_id = '. $this->data['location_id'] .' AND end_location_id = '. (int) $location_id .')';
	   $result = $this->db->sql_query($sql);
	   $row = $this->db->sql_fetchrow($result);
	   $this->db->sql_freeresult($result);

	   if ($row === false)
	   {
		   throw new \consim\core\exception\invalid_argument(array($location_id, 'ILLEGAL_CHARACTERS'));
	   }

	   $this->data['location_id'] = $location_id;

	   return $this;
   }

   /**
	* If user active?
	*
	* @return int active
	* @access public
	*/
	public function getActive()
	{
		return $this->getInteger($this->data['active']);
	}
	/**
	* Set Active
	*
	* @param bool $active
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setActive($active)
	{
		return $this->setInteger('active', $active, true, 1);
	}

	/**
	* Get Data from Figure
	*
	* @return ConsimFigure[]
	* @access public
	*/
	public function getFigureData()
	{
		return $this->figure_data;
	}

	/**
	 * Überprüft ob String auch im Figure existiert
	 * und fügt die ID-Nr ein
	 *
	 * @param string $varname
	 * @param string $string
	 * @return ConsimUser
	 * @access private
	 * @throws \consim\core\exception\invalid_argument
	 **/
	private function setFigure($varname, $string)
	{
		//$string not empty
		if(empty($string))
		{
			throw new \consim\core\exception\invalid_argument(array($string, 'FIELD_MISSING'));
		}

		foreach($this->figure_data as $element)
		{
			//Stimmen die Werte überein?
			if($element->getValue() == $string && $varname == $element->getGroups())
			{
				//Setze ID
				return $this->setInteger($varname . '_id', $element->getId());
			}
		}

		throw new \consim\core\exception\invalid_argument(array($varname, 'ILLEGAL_CHARACTERS'));
	}
}
