<?php
/**
 * @package ConSim for phpBB3.1
 *
 * @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
 * @copyright (c) 2016 Jonas Heitmann (kommodoree@googlemail.com)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace consim\core\service;

use consim\core\entity\Weather;
use consim\core\exception\invalid_argument;
use consim\core\exception\unexpected_value;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Operator for Inventory
 */
class WeatherService
{
	/** @var ContainerInterface */
	protected $container;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var  Weather */
	protected $weatherData = null;

	// current cacheTime = 3 hours
	protected $cacheTime = 10800;

	/**
	 * Constructor
	 *
	 * @param ContainerInterface					$container		Service container interface
	 * @param \phpbb\template\template				$template		Template object
	 * @return WeatherService
	 * @access public
	 */
	public function __construct(ContainerInterface $container,
		\phpbb\template\template $template)
	{
		$this->container = $container;
		$this->template = $template;

		return $this;
	}

	/**
	 * @param int $provinceId
	 * @return WeatherService
	 */
	public function loadWeatherFromProvinceID($provinceId)
	{
		//load Weather
		$this->weatherData = $this->container->get('consim.core.entity.weather')
			->load($provinceId);

		//if Cache up to date?
		if($this->weatherData->getLastUpdated() < (time() - $this->cacheTime))
		{
			$this->updateWeather();
		}

		return $this;
	}

	/**
	 * @return Weather
	 * @throws unexpected_value
	 */
	public function getCurrentWeather()
	{
		if($this->weatherData == null)
		{
			throw new unexpected_value('provinceId');
		}

		return $this->weatherData;
	}

	/**
	 * @return WeatherService
	 * @throws unexpected_value
	 */
	public function setWeatherWidget()
	{
		if($this->weatherData == null)
		{
			throw new unexpected_value('provinceId');
		}

		// Set output vars for display in the template
		$this->template->assign_vars(array(
			'TEMPERATURE'					=> $this->weatherData->getTemperature(),
			'WIND_SPEED'					=> $this->weatherData->getWindSpeed()*3.6,
			'WIND_DIRECTION'				=> $this->weatherData->getWindDirection(),
			'WEATHER'						=> $this->weatherData->getWeather(),
			'WEATHER_IMAGE'					=> 'http://openweathermap.org/img/w/'.$this->weatherData->getWeatherImage().'.png'
		));

		return $this;
	}

	protected function updateWeather()
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($curl, CURLOPT_URL, 'http://api.openweathermap.org/data/2.5/weather?id='.$this->weatherData->getOwmId().'&units=metric&lang=de&appid=APPID');
		$response = json_decode(curl_exec($curl),true);
		curl_close ($curl);

		if(!is_null($response))
		{
			if($response['cod'] == 401)
			{
				throw new invalid_argument($response['message']);
			}

			// Insert new data into database
			$rain = (isset($response['rain']['3h'])) ? $response['rain']['3h']/3 : 0;
			$rain = (isset($response['snow']['3h'])) ? $rain+($response['snow']['3h']/3) : $rain;
			$this->weatherData
				->setLastUpdated(time())
				->setWeather($response['weather'][0]['description'])
				->setWeatherImage($response['weather'][0]['icon'])
				->setRain($rain)
				->setTemperature($response['main']['temp'])
				->setWindSpeed($response['wind']['speed'])
				->setWindDirection($response['wind']['deg'])
				->setId($this->weatherData->getId())
				->save();
		}
		else
		{
			throw new invalid_argument('OWN_NO_CONNECTION');
		}
	}
}
