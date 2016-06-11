<?php
/**
* @package ConSim for phpBB3.1
*
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace consim\core\entity;
/**
* Abstract Entity for all Entities
*/
abstract class abstractEntity
{
	/**
	* All of fields of this objects
	*
	**/
	protected static $fields;

	/**
	* Some fields must be unsigned (>= 0)
	**/
	protected static $validate_unsigned;

	/**
	* Data for this entity
	* @access protected
	*/
	protected $data;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/**
	* Import data for this game
	*
	* Used when the data is already loaded externally.
	* Any existing data on this game is over-written.
	* All data is validated and an exception is thrown if any data is invalid.
	*
	* @param mixed[] $data Data array, typically from the database
	* @return self
	* @access public
	* @throws \consim\core\exception\invalid_argument
	* @throws \consim\core\exception\out_of_bounds
	*/
	public function import($data)
	{
		// Clear out any saved data
		$this->data = array();

		// Go through the basic fields and set them to our data array
		foreach (static::$fields as $field => $type)
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

		foreach (static::$validate_unsigned as $field)
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
	* Get String for output
	*
	* @param string $string Get the string for output
	* @return string
	* @access protected
	*/
	protected function getString($string)
	{
		return (isset($string)) ? (string) $string : '';
	}

	/**
	* Set a string to data
	*
	* @param string $varname Name of variable in data array
	* @param string $string New value of $varname
	* @param integer $max_char Allowed number of characters; Default: 255
	* @param integer $min_char Minimum number of characters; Default: 0
	* @return abstractEntity $this object for chaining calls; load()->set()->save()
	* @access protected
	* @throws \consim\core\exception\invalid_argument
	* @throws \consim\core\exception\unexpected_value
	*/
	protected function setString($varname, $string, $max_char = 255, $min_char = 0)
	{
		// Enforce a string
		$string = (string) $string;
		$varname = (string) $varname;

		//Enforce a integer
		$max_char = (integer) $max_char;
		$min_char = (integer) $min_char;

		if (strlen($string) <= $min_char)
		{
			throw new \consim\core\exception\invalid_argument(array($varname, 'TOO_SHORT'));
		}
		// We limit the name length to $characters characters
		if (truncate_string($string, $max_char) != $string)
		{
			throw new \consim\core\exception\unexpected_value(array($varname, 'TOO_LONG'));
		}
		// Set the name on our data array
		$this->data[$varname] = $string;
		return $this;
	}
	/**
	* Get Integer for output
	*
	* @param string $integer Get the Integer for output
	* @return Integer
	* @access protected
	*/
	protected function getInteger($integer)
	{
		return (isset($integer)) ? (int) $integer : 0;
	}
	/**
	* Set a Integer to data
	*
	* @param string $varname Name of variable in data array
	* @param integer $integer New value of $varname
	* @param boolean $unsigned If must the integer unsigned?; Default: true
	* @param int $max_int Maximum of Integer
	* @return abstractEntity $this object for chaining calls; load()->set()->save()
	* @access protected
	* @throws \consim\core\exception\out_of_bounds
	*/
	protected function setInteger($varname, $integer, $unsigned = true, $max_int = PHP_INT_MAX)
	{
		// Enforce a integer
		$integer = (integer) $integer;
		$max_int = (integer) $max_int;
		// Enforce a string
		$varname = (string) $varname;
		// Enforce a bool
		$unsigned = (boolean) $unsigned;

		// If the data is less than 0, it's not unsigned and we'll throw an exception
		if (($unsigned && $integer < 0) || ($integer > $max_int))
		{
			throw new \consim\core\exception\out_of_bounds($integer);
		}
		// Set the order_id on our data array
		$this->data[$varname] = $integer;
		return $this;
	}
}
