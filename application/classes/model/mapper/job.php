<?php defined('SYSPATH') or die('No direct script access.');

class Model_Mapper_Job extends Model_Mapper
{
	protected $_table = 'dc_ff2fb_job';
	
	/**
	 * Adds a new job
	 *
	 * @param array $data
	 */
	public function add(array $data)
	{
		return $this->insert($data);
	}
	
	/**
	 * Returns a job via job id
	 *
	 * @param int $jobId
	 * @return array
	 */
	public function get($jobId)
	{
		$query = DB::select()
			->from($this->_table)
			->where('job_id', '=', $jobId);
			
		return $this->fetchRow($query);
	}
	
	/**
	 * Returns all feeds by user
	 * 
	 * @param string $userId
	 * @return array
	 */
	public function getByUser($userId)
	{
		$query = DB::select()
			->from($this->_table)
			->where('user_id', '=', $userId);
		
		return $this->fetchAll($query);
	}
}