<?php defined('SYSPATH') or die('No direct script access.');

class Import_Buffer
{
	/**
	 * Maximum rows for the buffer to hold
	 *
	 * @var int
	 */
	protected $_max = 200;
	
	/**
	 * Buffered feeds for import
	 *
	 * @var mixed
	 */
	protected $_data = array();
	
	/**
	 * __construct()
	 *
	 * @param array $options
	 * @return void
	 */
	public function __construct(array $options = null)
	{
		// check for max and other options
		$opts = array('max');
		foreach ($opts as $opt)
		{
			if (isset($options[$opt]))
			{
				$this->$opt = $options[$opt];
			}			
		}
	}
	
	/**
	 * Returns the buffer data
	 *
	 * @return array
	 */
	public function get_data()
	{
		return $this->_data;
	}
	
	/**
	 * Adds a new record to the buffer
	 *
	 * @param array $rec
	 * @return $this
	 * @throws Exception
	 */
	public function add(array $rec)
	{
		if ($this->count() >= ($this->_max - 1))
		{
			throw new Exception('Buffer is full at ' . $this->_max . ' records');
		}
		
		$this->_data += $rec;
		
		return $this;
	}
	
	/**
	 * Returns the total count of data
	 *
	 * @return int
	 */
	public function count()
	{
		return count($this->_data);
	}
	
	/**
	 * Overwrites the buffer's data by setting a new data
	 *
	 * @param array $data
	 * @return $this
	 */
	public function set(array $data)
	{
		$this->_data = $data;
		
		return $this;
	}
	
	/**
	 * Returns true if the buffer is full
	 *
	 * @return boolean
	 */
	public function full()
	{
		return $this->count() >= $this->_max;
	}
	
	/**
	 * Returns true if buffer is empty
	 *
	 * @return boolean
	 */
	public function is_empty()
	{
		return empty($this->_data);
	}
	
	/**
	 * Clears the buffer
	 *
	 * @return $this
	 */
	public function clear()
	{
		$this->_data = array();
		
		return $this;
	}
}