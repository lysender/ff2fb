<?php defined('SYSPATH') or die('No direct script access.');

class Model_UserRole extends Sprig
{
	/**
	 * @var string
	 */
	protected $_table = 'dc_user_role';
	
	/**
	 * Composite primary key for user roles
	 */
	protected $_primary_key = array('user_id', 'role_id');
	
	/**
	 * Initialize
	 */
	protected function _init()
	{
		$this->_fields += array(
			'user_id'			=> new Sprig_Field_Integer,
			'role_id' 			=> new Sprig_Field_Integer
		);
	}
}