<?php
/**
* @package ConSim for phpBB3.1
*
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @copyright (c) 2016 Jonas Heitmann (kommodoree@googlemail.com)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*/

namespace consim\core\entity;

/**
* Entity for news entries
*/
class Weather extends abstractEntity
{
	/**
	* All of fields of this objects
	*
	**/
	protected static $fields = array(
		'prvnce_id'					=> 'integer',
		'owm_id'					=> 'integer',
		'last_updated'				=> 'string',
		'weather'					=> 'string',
		'weather_image'				=> 'string',
		'rain'						=> 'string',
		'temperature'				=> 'string',
		'wind_speed'				=> 'integer',
		'wind_direction'			=> 'integer',
	);

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned = array(
		'prvnce_id',
		'owm_id',
		'wind_speed',
		'wind_direction',
	);

	protected $data;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/**
	* The database table the consim user data are stored in
	* @var string
	*/
	protected $consim_weather_table;

   /**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface    $db						Database object
	* @param string                               $consim_weather_table		Name of the table used to store data
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db, $consim_weather_table)
	{
		$this->db = $db;
		$this->consim_weather_table = $consim_weather_table;
	}

	/**
	* Load the data from the database for this object
	*
	* @param int $id user identifier
	* @return Weather $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function load($id)
	{
		$sql = 'SELECT prvnce_id, owm_id, last_updated, weather, weather_image, rain, temperature, wind_speed, wind_direction
			FROM ' . $this->consim_weather_table . '
			WHERE prvnce_id = ' . (int) $id;
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
	* Insert the Data for the first time
	*
	* Will throw an exception if the data was already inserted (call save() instead)
	*
	* @return Weather $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function insert()
	{
		if (!empty($this->data['prvnce_id']))
		{
			// The data already exists
			throw new \consim\core\exception\out_of_bounds('id');
		}

		// Make extra sure there is no id set
		unset($this->data['prvnce_id']);

		// Insert the data to the database
		$sql = 'INSERT INTO ' . $this->consim_weather_table . ' ' . $this->db->sql_build_array('INSERT', $this->data);
		$this->db->sql_query($sql);

		// Set the id using the id created by the SQL insert
		$this->data['prvnce_id'] = (int) $this->db->sql_nextid();

		return $this;
	}

	/**
	* Save the current settings to the database
	*
	* This must be called before closing or any changes will not be saved!
	* If adding a data (saving for the first time), you must call insert() or an exeception will be thrown
	*
	* @return Weather $this object for chaining calls; load()->set()->save()
	* @access public
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function save()
	{
		if (empty($this->data['prvnce_id']))
		{
			// The data does not exist
			throw new \consim\core\exception\out_of_bounds('id');
		}

		$sql = 'UPDATE ' . $this->consim_weather_table . '
			SET ' . $this->db->sql_build_array('UPDATE', $this->data) . '
			WHERE prvnce_id = ' . $this->getId();
		$this->db->sql_query($sql);

		return $this;
	}

	/**
	* Get province ID
	*
	* @return int ID
	* @access public
	*/
	public function getId()
	{
		return $this->getInteger($this->data['prvnce_id']);
	}
	
	/**
	* Set province ID
	*
	* @param int $id
	* @return Weather
	* @access public
	*/
	public function setId($id)
	{
		return $this->setInteger('prvnce_id',$id);
	}
	
	/**
	* Get OpenWeatherMap ID
	*
	* @return int ID
	* @access public
	*/
	public function getOwmId()
	{
		return $this->getInteger($this->data['owm_id']);
	}
	
	/**
	* Set OpenWeatherMap ID
	*
	* @param int $id
	* @return Weather
	* @access public
	*/
	public function setOwmId($id)
	{
		return $this->setInteger('owm_id',$id);
	}

	/**
	* Get last update date
	*
	* @return string last_updated
	* @access public
	*/
	public function getLastUpdated()
	{
		return $this->getString($this->data['last_updated']);
	}
	
	/**
	* Set update date
	*
	* @param string $time
	* @return Weather
	* @access public
	*/
	public function setLastUpdated($time)
	{
		return $this->setString('last_updated',$time, 255, 2);
	}

	/**
	* Get the weather in a string
	*
	* @return string weather
	* @access public
	*/
	public function getWeather()
	{
		return $this->getString($this->data['weather']);
	}
	
	/**
	* Set weather in a string
	*
	* @param string $weather
	* @return Weather
	* @access public
	*/
	public function setWeather($weather)
	{
		return $this->setString('weather',$weather, 255, 2);
	}

	/**
	* Get the weather image
	*
	* @return string weatherImage
	* @access public
	*/
	public function getWeatherImage()
	{
		return $this->getString($this->data['weather_image']);
	}
	
	/**
	* Set the weather image
	*
	* @param string $image
	* @return Weather
	* @access public
	*/
	public function setWeatherImage($image)
	{
		return $this->setString('weather_image',$image, 255, 2);
	}
	
	/**
	* Get rain
	*
	* @return string rain
	* @access public
	*/
	public function getRain()
	{
		return $this->getString($this->data['rain']);
	}
	
	/**
	* Set rain
	*
	* @param string $rain
	* @return Weather
	* @access public
	*/
	public function setRain($rain)
	{
		return $this->setString('rain',$rain);
	}

	/**
	* Get temperature
	*
	* @return string temperature
	* @access public
	*/
	public function getTemperature()
	{
		return $this->getString($this->data['temperature']);
	}
	
	/**
	* Set temperature
	*
	* @param string $temperature
	* @return Weather
	* @access public
	*/
	public function setTemperature($temperature)
	{
		return $this->setString('temperature',$temperature);
	}
	
	/**
	* Get wind speed
	*
	* @return int Speed
	* @access public
	*/
	public function getWindSpeed()
	{
		return $this->getInteger($this->data['wind_speed']);
	}
	
	/**
	* Set wind speed
	*
	* @param int $speed
	* @return Weather
	* @access public
	*/
	public function setWindSpeed($speed)
	{
		return $this->setInteger('wind_speed',$speed);
	}

	/**
	* Get wind direction
	*
	* @return int Direction
	* @access public
	*/
	public function getWindDirection()
	{
		return $this->getInteger($this->data['wind_direction']);
	}
	
	/**
	* Set wind direction
	*
	* @param int $dir
	* @return Weather
	* @access public
	*/
	public function setWindDirection($dir)
	{
		return $this->setInteger('wind_direction',$dir);
	}
}
