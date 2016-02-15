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
      	'geschlecht'					=> 'integer',
      	'geburtsland'					=> 'integer',
      	'religion'						=> 'integer',
      	'haarfarbe'						=> 'integer',
      	'augenfarbe'					=> 'integer',
      	'besondere_merkmale'			=> 'integer',
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
        'location'                      => 'integer',
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
        'location',
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
	protected $consim_user_table;

	/**
	* The database table the person data are stored in
	* @var string
	*/
	protected $consim_person_table;

	/**
	* Variable to save the data from person table
	* @var array
	*/
	protected $figure_data;

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
		$sql = 'SELECT id, beschreibung, wert, translate
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
        switch($this->figure_data[$this->data['geburtsland']]->getWert())
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
        return $this->figure_data[$this->data['geschlecht']];
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
        return $this->figure_data[$this->data['geburtsland']];
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
        return $this->figure_data[$this->data['religion']];
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
        return $this->figure_data[$this->data['religion']];
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
        return $this->figure_data[$this->data['augenfarbe']];
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
        return $this->figure_data[$this->data['besondere_merkmale']];
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
	* @param string $sprache_tadsowisch
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setSpracheTadsowisch($sprache_tadsowisch)
	{
		return $this->setInteger('sprache_tadsowisch', $sprache_tadsowisch, true, 100);
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
	public function setSpracheBakirisch($sprache_bakirisch)
	{
		return $this->setInteger('sprache_bakirisch', $sprache_bakirisch, true, 100);
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
	public function setSpracheSuranisch($sprache_suranisch)
	{
		return $this->setInteger('sprache_suranisch', $sprache_suranisch, true, 100);
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
	public function setRhetorik($rhetorik)
	{
		return $this->setInteger('rhetorik', $rhetorik, true, 100);
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
 	public function setAdministration($administration)
 	{
 		return $this->setInteger('administration', $administration, true, 100);
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
	public function setWirtschaft($wirtschaft)
	{
		return $this->setInteger('wirtschaft', $wirtschaft, true, 100);
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
	public function setTechnik($technik)
	{
		return $this->setInteger('technik', $technik, true, 100);
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
	public function setNahkampf($nahkampf)
	{
		return $this->setInteger('nahkampf', $nahkampf, true, 100);
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
	public function setSchusswaffen($schusswaffen)
	{
		return $this->setInteger('schusswaffen', $schusswaffen, true, 100);
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
	public function setSprengmittel($sprengmittel)
	{
		return $this->setInteger('sprengmittel', $sprengmittel, true, 100);
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
	public function setMilitarkunde($militarkunde)
	{
		return $this->setInteger('militarkunde', $militarkunde, true, 100);
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
	public function setSpionage($spionage)
	{
		return $this->setInteger('spionage', $spionage, true, 100);
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
	public function setSchmuggel($schmuggel)
	{
		return $this->setInteger('schmuggel', $schmuggel, true, 100);
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
	public function setMedizin($medizin)
	{
		return $this->setInteger('medizin', $medizin, true, 100);
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
	public function setUberlebenskunde($uberlebenskunde)
	{
		return $this->setInteger('uberlebenskunde', $uberlebenskunde, true, 100);
	}

    /**
   * Get location
   *
   * @return id location
   * @access public
   */
   public function getLocation()
   {
       return $this->getInteger($this->data['location']);
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
   public function setLocation($location)
   {
       $sql = 'SELECT id
           FROM phpbb_consim_routes
           WHERE (start_id = '. (int) $location .' AND end_id = '. $this->data['location'] .')
                 OR (start_id = '. $this->data['location'] .' AND end_id = '. (int) $location .')';
       $result = $this->db->sql_query($sql);
       $row = $this->db->sql_fetchrow($result);
       $this->db->sql_freeresult($result);

       if ($row === false)
       {
           throw new \consim\core\exception\invalid_argument(array($location, 'ILLEGAL_CHARACTERS'));
       }

       $this->data['location'] = $location;

       return this;
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
			throw new \consim\core\exception\invalid_argument(array($varname, 'FIELD_MISSING'));
		}

		foreach($this->figure_data as $element)
		{
			//Stimmen die Werte überein?
			if($element->getWert() == $string && $varname == $element->getBeschreibung())
			{
				//Setze ID
				return $this->setInteger($varname, $element->getId());
			}
		}

		throw new \consim\core\exception\invalid_argument(array($varname, 'ILLEGAL_CHARACTERS'));
	}
}
