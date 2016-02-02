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
      	'geschlecht'					=> 'string',
      	'geburtsland'					=> 'string',
      	'religion'						=> 'string',
      	'haarfarbe'						=> 'string',
      	'augenfarbe'					=> 'string',
      	'besondere_merkmale'			=> 'string',
      	'sprache_tadsowisch'			=> 'integer',
      	'sprache_bakirisch'				=> 'integer',
      	'sprache_suranisch'				=> 'integer',
      	'rhetorik'						=> 'integer',
      	'wirtschaft'					=> 'integer',
      	'technik'						=> 'integer',
      	'nahkampf'						=> 'integer',
		'schusswaffen'					=> 'integer',
      	'militarkunde'					=> 'integer',
      	'spionage'						=> 'integer',
		'medizin'						=> 'integer',
      	'uberlebenskunde'				=> 'integer',
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
      	'wirtschaft',
      	'technik',
      	'nahkampf',
		'schusswaffen',
      	'militarkunde',
      	'spionage',
		'medizin',
      	'uberlebenskunde',
	);

	protected $data;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/**
	* The database table the consim user data are stored in
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
			WHERE user_id = ' . (int) $id;
		$result = $this->db->sql_query($sql);
		$this->data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($data === false)
		{
			throw new \consim\core\exception\out_of_bounds('id');
		}

		return $this;
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
	public function insert()
	{
		if (!empty($this->data['user_id']))
		{
			// The data already exists
			throw new \consim\core\exception\out_of_bounds('user_id');
		}

		// Make extra sure there is no id set
		unset($this->data['user_id']);

		// Insert the data to the database
		$sql = 'INSERT INTO ' . $this->consim_user_table . ' ' . $this->db->sql_build_array('INSERT', $this->data);
		$this->db->sql_query($sql);

		// Set the id using the id created by the SQL insert
		$this->data['user_id'] = (int) $this->db->sql_nextid();

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
		return $this->setString('vorname', $vorname);
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
		return $this->setString('nachname', $nachname);
	}

   /**
	* Get geschlecht
	*
	* @return string geschlecht
	* @access public
	*/
	public function getGeschlecht()
	{
		return $this->getString($this->data['geschlecht']);
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
		return $this->setString('geschlecht', $geschlecht, 1);
	}

   /**
	* Get geburtsland
	*
	* @return string geburtsland
	* @access public
	*/
	public function getGeburtsland()
	{
		return $this->getString($this->data['geburtsland']);
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
		return $this->setString('geburtsland', $geburtsland, 3);
	}

   /**
	* Get religion
	*
	* @return string religion
	* @access public
	*/
	public function getReligion()
	{
		return $this->getString($this->data['religion']);
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
		return $this->setString('religion', $religion);
	}

   /**
	* Get haarfarbe
	*
	* @return string religion
	* @access public
	*/
	public function getHaarfarbe()
	{
		return $this->getString($this->data['haarfarbe']);
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
		return $this->setString('haarfarbe', $haarfarbe);
	}

   /**
	* Get augenfarbe
	*
	* @return string augenfarbe
	* @access public
	*/
	public function getAugenfarbe()
	{
		return $this->getString($this->data['augenfarbe']);
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
		return $this->setString('augenfarbe', $augenfarbe);
	}

   /**
	* Get Besondere Merkmale
	*
	* @return string Besondere Merkmale
	* @access public
	*/
	public function getBesondereMerkmale()
	{
		return $this->getString($this->data['besondere_merkmale']);
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
		return $this->setString('besondere_merkmale', $besondere_merkmale);
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
	* @param string $spionage
	* @return ConsimUser $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\unexpected_value
	*/
	public function setUberlebenskunde($uberlebenskunde)
	{
		return $this->setInteger('uberlebenskunde', $uberlebenskunde, true, 100);
	}
}
