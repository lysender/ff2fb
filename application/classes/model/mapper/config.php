<?php defined('SYSPATH') or die('No direct script access.');

class Model_Mapper_Config extends Model_Mapper
{	
	protected $_table = 'dc_config';
	
	/**
	 * Fetches a configuration from a given id
	 *
	 * @param int $configId
	 * @return array
	 */
	public function get($configId)
	{
		$query = DB::select()
			->from($this->_table)
			->where('id', '=', $configId);
			
		return $this->fetchRow($query);
	}
	
	/**
	 * Updates a configuration record
	 *
	 * @param int $configId
	 * @param array $data
	 * @return int $affectedRows
	 */
	public function save($configId, $data)
	{
		$query = DB::update($this->_table)
			->where('id', '=', $configId);
		
		$query->set($data);
		
		return $this->update($query);
	}
	
	/**
	 * Returns true if and only if config exists
	 *
	 * @param int $configId
	 * @return boolean
	 */
	public static function exists($configId)
	{
		$mapper = self;
		return (boolean)$mapper->get($configId);
	}
}