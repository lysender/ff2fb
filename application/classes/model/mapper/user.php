<?php defined('SYSPATH') or die('No direct script access.');

class Model_Mapper_User extends Model_Mapper
{
	/**
	 * @var string
	 */
	protected $_table = 'dc_user';
	
	/**
	 * Adds a new record
	 * 
	 * @param array $data
	 * @return mixed $result
	 */
	public function add(array $data)
	{
		return $this->insert($data);
	}
	
	/**
	 * Returns user using id key
	 * 
	 * @param string $id Binary format
	 * @return array | boolean false
	 */
	public function getById($id)
	{
		$query = DB::select()
			->from($this->_table)
			->where('user_id', '=', $id);
		return $this->fetchRow($query);
	}
	
	/**
	 * Returns a user record using username key
	 * 
	 * @param string $username
	 * @return array | boolean false
	 */
	public function getByUsername($username)
	{
		$query = DB::select()
			->from($this->_table)
			->where('username', '=', $username);
		return $this->fetchRow($query);
	}
	
	/**
	 * Returns a user record using the email key
	 *
	 * @param string $email
	 * @return array | boolean false
	 */
	public function getByEmail($email)
	{
		$query = DB::select()
			->from($this->_table)
			->where('email', '=', $email);
			
		return $this->fetchRow($query);
	}
	
	/**
	 * Updates a user records
	 * 
	 * @param string $id
	 * @param array $data
	 * @return boolean
	 */
	public function save($id, $data)
	{
		$query = DB::update($this->_table)->where('user_id', '=', $id);
		$query->set($data);
		
		return $this->update($query);
	}
	
	/**
	 * Deletes a user record
	 * 
	 * @param int $id
	 * @return int $affected_rows
	 */
	public function delete($id)
	{
		$query = DB::delete($this->_table)->where('user_id', '=', $id);
		return $this->deleteRecord($query);
	}
}