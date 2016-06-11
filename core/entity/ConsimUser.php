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
		'sprache_tadsowisch'			=> 'integer',
		'sprache_bakirisch'				=> 'integer',
		'sprache_suranisch'				=> 'integer',
		'rhetorik'						=> 'integer',
		'administration'				=> 'integer',
		'wirtschaft'					=> 'integer',
		'technik'						=> 'integer',
		'nahkampf'						=> 'integer',
		'schusswaffen'					=> 'integer',
		'sprengmittel'					=> 'integer',
		'militarkunde'					=> 'integer',
		'spionage'						=> 'integer',
		'schmuggel'						=> 'integer',
		'medizin'						=> 'integer',
		'uberlebenskunde'				=> 'integer',
		'location_id'                   => 'integer',
		'active'                        => 'bool'
	);

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned = array(
		'user_id',
		'sprache_tadsowisch',
		'sprache_bakirisch',
		'sprache_suranisch',
		'rhetorik',
		'administration',
		'wirtschaft',
		'technik',
		'nahkampf',
		'schusswaffen',
		'sprengmittel',
		'militarkunde',
		'spionage',
		'schmuggel',
		'medizin',
		'uberlebenskunde',
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
	* @var array
	*/
	private $figure_data;

	//extra language skill
	const EXTRA_LANG = 25;

   /**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface    $db                 Database object
	* @param ContainerInterface                	  $container            Service container interface
	* @param string                               $consim_user_table  Name of the table used to store consim user data
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
	* @return object $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\user\exception\out_of_bounds
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
	* @return array with ConsimPerson Objects
	* @access private
	*/
	private function figure_load()
	{
		$sql = 'SELECT id, groups, value, name
			FROM ' . $this->consim_person_table;
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$figure_data[$row['id']] = $this->container->get('consim.core.entity.ConsimFigure')
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

		//Add extra language skill
		switch($this->figure_data[$this->data['geburtsland_id']]->getValue())
		{
			case 'frt': $this->data['sprache_tadsowisch'] = $this->data['sprache_tadsowisch'] + self::EXTRA_LANG;
			break;
			case 'bak': $this->data['sprache_bakirisch'] = $this->data['sprache_bakirisch'] + self::EXTRA_LANG;
			break;
			case 'sur': $this->data['sprache_suranisch'] = $this->data['sprache_suranisch'] + self::EXTRA_LANG;
			break;
		}

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
	* @return string vorname
	* @access public
	*/
	public function getUserId()
	{
		return $this->getString($this->data['user_id']);
	}

	/**
	* Get vorname
	*
	* @return string vorname
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
	* Get nachname
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
	* @return object ConSimFigure
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
	* @return object ConSimFigure
	* @access public
	*/
	public function getGeburtsland()
	{
		//return $this->getString($this->data['geburtsland']);
		return $this->figure_data[$this->data['geburtsland_id']];
	}
	/**
	* Set geburtsland
	*
	* @param string $geschlecht
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
	* @return object ConSimFigure
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
	* @param string $geschlecht
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
	* @return object ConSimFigure
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
	* @return object ConSimFigure
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
	* @return object ConSimFigure
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
	* Get Sprache Tadsowisch
	*
	* @return string Sprache Tadsowisch
	* @access public
	*/
	public function getSpracheTadsowisch()
	{
		return $this->getInteger($this->data['sprache_tadsowisch']);
	}
	/**
	* Set Sprache Tadsowisch
	*
	* @param int $level
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setSpracheTadsowisch($level)
	{
		return $this->setInteger('sprache_tadsowisch', $level, true, 100);
	}

   /**
	* Get Sprache bakirisch
	*
	* @return string Sprache bakirisch
	* @access public
	*/
	public function getSpracheBakirisch()
	{
		return $this->getInteger($this->data['sprache_bakirisch']);
	}
	/**
	* Set Sprache bakirisch
	*
	* @param string $sprache_bakirisch
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setSpracheBakirisch($level)
	{
		return $this->setInteger('sprache_bakirisch', $level, true, 100);
	}

   /**
	* Get Sprache Suranisch
	*
	* @return string Sprache Suranisch
	* @access public
	*/
	public function getSpracheSuranisch()
	{
		return $this->getInteger($this->data['sprache_suranisch']);
	}
	/**
	* Set Sprache Suranisch
	*
	* @param string $sprache_suranisch
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setSpracheSuranisch($level)
	{
		return $this->setInteger('sprache_suranisch', $level, true, 100);
	}

   /**
	* Get rhetorik
	*
	* @return string rhetorik
	* @access public
	*/
	public function getRhetorik()
	{
		return $this->getInteger($this->data['rhetorik']);
	}
	/**
	* Set rhetorik
	*
	* @param string $rhetorik
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
<<<<<<< HEAD
	public function setRhetorik($level)
	{
		return $this->setInteger('rhetorik', $level, true, 100);
=======
	public function setRhetorik($rhetorik)
	{
		return $this->setInteger('rhetorik', $rhetorik, true, 100);
>>>>>>> refs/remotes/origin/master
	}

	/**
	* Get Administration
	*
	* @return string Administration
	* @access public
	*/
	public function getAdministration()
	{
		return $this->getInteger($this->data['administration']);
	}
	/**
	* Set Administration
	*
	* @param string $administration
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
<<<<<<< HEAD
	public function setAdministration($level)
	{
		return $this->setInteger('administration', $level, true, 100);
=======
	public function setAdministration($administration)
	{
		return $this->setInteger('administration', $administration, true, 100);
>>>>>>> refs/remotes/origin/master
	}

   /**
	* Get wirtschaft
	*
	* @return string wirtschaft
	* @access public
	*/
	public function getWirtschaft()
	{
		return $this->getInteger($this->data['wirtschaft']);
	}
	/**
	* Set wirtschaft
	*
	* @param string $wirtschaft
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setWirtschaft($level)
	{
		return $this->setInteger('wirtschaft', $level, true, 100);
	}

   /**
	* Get technik
	*
	* @return string technik
	* @access public
	*/
	public function getTechnik()
	{
		return $this->getInteger($this->data['technik']);
	}
	/**
	* Set technik
	*
	* @param string $technik
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setTechnik($level)
	{
		return $this->setInteger('technik', $level, true, 100);
	}

   /**
	* Get nahkampf
	*
	* @return string nahkampf
	* @access public
	*/
	public function getNahkampf()
	{
		return $this->getInteger($this->data['nahkampf']);
	}
	/**
	* Set nahkampf
	*
	* @param string $nahkampf
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setNahkampf($level)
	{
		return $this->setInteger('nahkampf', $level, true, 100);
	}

	/**
	* Get schusswaffen
	*
	* @return string schusswaffen
	* @access public
	*/
	public function getSchusswaffen()
	{
		return $this->getInteger($this->data['schusswaffen']);
	}
	/**
	* Set schusswaffen
	*
	* @param string $schusswaffen
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setSchusswaffen($level)
	{
		return $this->setInteger('schusswaffen', $level, true, 100);
	}

	/**
	* Get Sprengmittel
	*
	* @return string Sprengmittel
	* @access public
	*/
	public function getSprengmittel()
	{
		return $this->getInteger($this->data['sprengmittel']);
	}
	/**
	* Set Sprengmittel
	*
	* @param string $sprengmittel
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setSprengmittel($level)
	{
		return $this->setInteger('sprengmittel', $level, true, 100);
	}

   /**
	* Get militarkunde
	*
	* @return string militarkunde
	* @access public
	*/
	public function getMilitarkunde()
	{
		return $this->getInteger($this->data['militarkunde']);
	}
	/**
	* Set militarkunde
	*
	* @param string $militarkunde
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setMilitarkunde($level)
	{
		return $this->setInteger('militarkunde', $level, true, 100);
	}

   /**
	* Get spionage
	*
	* @return string spionage
	* @access public
	*/
	public function getSpionage()
	{
		return $this->getInteger($this->data['spionage']);
	}
	/**
	* Set militarkunde
	*
	* @param string $spionage
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setSpionage($level)
	{
		return $this->setInteger('spionage', $level, true, 100);
	}

	/**
	* Get Schmuggel
	*
	* @return string Schmuggel
	* @access public
	*/
	public function getSchmuggel()
	{
		return $this->getInteger($this->data['schmuggel']);
	}
	/**
	* Set Schmuggel
	*
	* @param string $schmuggel
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setSchmuggel($level)
	{
		return $this->setInteger('schmuggel', $level, true, 100);
	}

	/**
	* Get Medizin
	*
	* @return string Medizin
	* @access public
	*/
	public function getMedizin()
	{
		return $this->getInteger($this->data['medizin']);
	}
	/**
	* Set Medizin
	*
	* @param string $medizin
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setMedizin($level)
	{
		return $this->setInteger('medizin', $level, true, 100);
	}

	/**
	* Get uberlebenskunde
	*
	* @return string uberlebenskunde
	* @access public
	*/
	public function getUberlebenskunde()
	{
		return $this->getInteger($this->data['uberlebenskunde']);
	}
	/**
	* Set uberlebenskunde
	*
	* @param string $uberlebenskunde
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setUberlebenskunde($level)
	{
		return $this->setInteger('uberlebenskunde', $level, true, 100);
	}

	/**
<<<<<<< HEAD
	* Get Bakirischer Rubel
	*
	* @return string Bakirischer Rubel
	* @access public
	*/
	public function getBakRubel()
	{
		return $this->getInteger($this->data['bak_rubel']);
	}
	/**
	* Set Bakirischer Rubel
	*
	* @param string $rubel
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setBakRubel($rubel)
	{
		return $this->setInteger('bak_rubel', $rubel);
	}

	/**
	* Get Suranischer Dinar
	*
	* @return string Suranischer Dinar
	* @access public
	*/
	public function getSurDinar()
	{
		return $this->getInteger($this->data['sur_dinar']);
	}
	/**
	* Set Suranischer Dinar
	*
	* @param string $dinar
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setSurDinar($dinar)
	{
		return $this->setInteger('sur_dinar', $dinar);
	}

	/**
	* Get Tadsowischer Dollar
	*
	* @return string Tadsowischer Dollar
	* @access public
	*/
	public function getFrtDollar()
	{
		return $this->getInteger($this->data['frt_dollar']);
	}
	/**
	* Set Tadsowischer Dollar
	*
	* @param string $dollar
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setFrtDollar($dollar)
	{
		return $this->setInteger('frt_dollar', $dollar);
	}

	/**
=======
>>>>>>> refs/remotes/origin/master
   * Get location
   *
   * @return id location
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
   * @param int $location
   * @return ConsimUser $this object for chaining calls; load()->set()->save()
   * @access public
   * @throws \consim\core\exception\unexpected_value
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

	   return this;
   }

   /**
	* If user active?
	*
	* @return bool active
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
		$acitve = (int) $active;
		return $this->setInteger('active', $active, true, 1);
	}

	/**
	* Get Data from Figure
	*
	* @return Array of ConSim-Figure objects
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
	* @return bool
	* @access private
	*/
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
