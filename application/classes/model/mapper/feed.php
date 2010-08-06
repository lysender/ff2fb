<?php defined('SYSPATH') or die('No direct script access.');

class Model_Mapper_Feed extends Model_Mapper
{
	protected $_table = 'dc_ff2fb_feed';
	
	/**
	 * Adds a new feed
	 *
	 * @param array $data
	 */
	public function add(array $data)
	{
		return $this->insert($data);
	}
	
	/**
	 * Returns a feed via feed id
	 * Feed ID is a binary string
	 *
	 * @param string $feedId
	 * @return array
	 */
	public function get($feedId)
	{
		$query = DB::select()
			->from($this->_table)
			->where('feed_id', '=', $feedId);
			
		return $this->fetchRow($query);
	}
	
	/**
	 * Returns true if and only if the feed exits
	 *
	 * @param string $feedId
	 * @return boolean
	 */
	public function feedExists($feedId)
	{
		$query = DB::select('feed_id')
			->from($this->_table)
			->where('feed_id', '=', $feedId);
			
		$result = $this->fetchRow($query);		
		return (boolean)$result;
	}
	
	/**
	 * Returns all feeds by batch
	 * Also filters by user when user id is present
	 * Batch id and user id are both binary string
	 * 
	 * @param string $batchId
	 * @param string $userId
	 * @return array
	 */
	public function getByBatch($batchId, $userId = null)
	{
		$query = DB::select()
			->from($this->_table)
			->where('batch_id', '=', $batchId);
			
		if ($userId)
		{
			$query->where('user_id', '=', $userId);
		}
		
		return $this->fetchAll($query);
	}
}