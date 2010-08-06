<?php defined('SYSPATH') or die('No direct script access.');

class Model_Mapper_UserRole extends Model_Mapper
{
	/**
	 * @var string
	 */
	protected $_table = 'dc_user_role';
	
	/**
	 * Adds a new user role record
	 *
	 * @param array $data
	 * @return mixed $primaryKey
	 */
	public function add(array $data)
	{
		return $this->insert($data);
	}
	
	/**
	 * Retrieves a user role record by its userid and roleId
	 *
	 * @param int $userId
	 * @param int $roleId
	 * @return array|boolean false
	 */
	public function get($userId, $roleId)
	{
		$query = DB::select()
			->from($this->_table)
			->where('user_id', '=', $userId)
			->where('role_id', '=', $roleId);
		
		return $this->fetchRow($query);
	}
	
	/**
	 * Retrieves user's role records by its userId
	 *
	 * @param string $userId
	 * @return array|boolean false
	 */
	public function getUserRoles($userId)
	{
		$query = DB::select()
			->from($this->_table)
			->where('user_id', '=', $userId);
			
		return $this->fetchAll($query);
	}
	
	
	/**
	 * Updates user's role record
	 *
	 * @param int $userId
	 * @param array $data
	 * @return int $affectedRows
	 */
	public function save($userId, $roleId, array $data)
	{
		$query = DB::update($this->_table)
			->where('user_id', '=', $userId)
			->where('role_id', '=', $roleId);
			
		$query->set($data);
		return $this->update($query);
	}
	
	/**
	 * Deletes user's role record or records
	 * 
	 * @param int $userId
	 * @param int $roleId Optional
	 * @return int $affectedRows
	 */
	public function delete($userId, $roleId = null)
	{
		$query = DB::delete($this->_table)
			->where('user_id', '=', $userId);
			
		if ($roleId)
		{
			$query->where('role_id', '=', $roleId);
		}
		
		return $this->deleteRecord($query);
	}
}