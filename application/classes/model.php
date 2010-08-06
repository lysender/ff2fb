<?php defined('SYSPATH') or die('No direct script access.');

/**
 * All uuids at model level are string. All uuid at
 * database level are binary uuid. All ACL fields such as role_id,
 * resource_id and privilege_name are either string uuid or null.
 * Only in mapper those fields are converted to binary if
 * applicable
 */
abstract class Model
{
	/**
	 * Holds object data
	 *
	 * @var array
	 */
	protected $_data = array();
	
	/**
	 * Holds object fields
	 *
	 * @var array
	 */
	protected $_fields = array();
	
	/**
	 * Holds the methods for filtering
	 * the data before assigning to object fields
	 *
	 * @var array
	 */
	protected $_filters = array();
	
	/**
	 * Validation rules
	 * 
	 * @var array
	 */
	protected $_rules;
	
	/**
	 * Generic getter
	 */
	public function __get($name)
	{
		if (in_array($name, $this->_fields))
		{
			if (isset($this->_data[$name]))
			{
				return $this->_data[$name];
			}
			else
			{
				return null;
			}
		}
		throw new Kohana_Exception("Model property $name does not exists.");
	}
	
	/**
	 * Generic setter
	 */
	public function __set($name, $value)
	{
		if (in_array($name, $this->_fields))
		{
			$this->_data[$name] = $value;
			
			return $this;
		}
		
		throw new Kohana_Exception("Model property $name does not exists.");
	}
	
	/**
	 * Returns a validator for the current model
	 *
	 * @param array $data
	 * @param string $mode
	 * @return Kohana_Validate
	 */
	abstract public function validator(array $data, $mode);
	
	/**
	 * Extracts the values from data whose fields appears
	 * on the fields list and returns it. Anything not included will
	 * have the value of null
	 *
	 * Anything that gets in must be filtered first
	 *
	 * @param array $data
	 * @return array
	 */
	public function extract(array $data)
	{		
		$result = array();
		foreach ($this->_fields as $f)
		{
			$result[$f] = null;
			if (array_key_exists($f, $data))
			{
				$result[$f] = $data[$f];
			}
		}
		
		return $result;
	}
	
	/**
	 * Sets the values into the object's fields
	 * Anything that gets in must be filtered first
	 *
	 * @param array $values
	 * @return Model Provides fluent interface
	 */
	public function set(array $values)
	{
		foreach ($values as $f => $val)
		{
			if (in_array($f, $this->_fields))
			{
				$this->_data[$f] = $val;
			}
		}
		
		return $this;
	}
	
	/**
	 * Converts the object into an array
	 * and returns it
	 *
	 * @return array
	 */
	public function toArray()
	{
		foreach ($this->_fields as $f)
		{
			if (!array_key_exists($f, $this->_data))
			{
				$this->_data[$f] = null;
			}
		}
		
		return $this->_data;
	}
	
	/**
	 * Cast to on/off the value passed
	 * Since kohana's not_empty interprets 0 as empty
	 * we will cast to string so that 0 becomes '0'
	 *
	 * @param mixed $value
	 * @return int
	 */
	public static function toOnOff($value)
	{
		return (string)(int)$value;
	}
	
	/**
	 * Checks if the given value is a zero / null or
	 * empty uuid
	 *
	 * @param mixed $value
	 * @return boolean
	 */
	public static function isZeroUuid($value)
	{
		if (!$value)
		{
			return true;
		}
		
		if (preg_replace('/[0-]/', '', $value) == str_repeat('0', 32))
		{
			return true;
		}
		
		$uuid = Dc_Uuid::stringUuid($value);
		if ($uuid && str_replace('-', '', $uuid->string) == str_repeat('0', 32))
		{
			return true;
		}
		
		return FALSE;
	}
	
	/**
	 * Converts a binary uuid to string uuid if applicable
	 * and null if not a valid uuid or is zero
	 *
	 * @param mixed $value
	 * @return string
	 */
	public static function toStringUuid($value)
	{
		if (!$value)
		{
			return null;
		}
		
		$uuid = Dc_Uuid::stringUuid($value);
		if (!$uuid)
		{
			return null;
		}
		
		if (self::isZeroUuid($uuid->string))
		{
			return null;
		}
		
		return $uuid->string;
	}
	
	/**
	 * Returns a zero string uuid
	 *
	 * @return string
	 */
	public static function zeroStringUuid()
	{
		return '00000000-0000-0000-0000-000000000000';
	}
}