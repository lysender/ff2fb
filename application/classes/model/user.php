<?php defined('SYSPATH') or die('No direct script access.');

class Model_User extends Sprig
{	
	/**
	 * Salt for password hashing
	 * 
	 * @var string
	 */
	protected $_salt = 'jqy8cGob';
	
	/**
	 * @var string
	 */
	protected $_table = 'dc_user';
	
	/**
	 * Column name used for select element where user_id is the value and
	 * username is the label or title
	 *
	 * @var string
	 */
	protected $_title_key = 'username';
	
	/**
	 * Initialize
	 */
	protected function _init()
	{
		$this->_fields += array(
			'user_id' 			=> new Sprig_Field_Auto,
			'username' 			=> new Sprig_Field_Char(array(
				'min_length' 		=> 6,
				'max_length' 		=> 16,
				'unique' 			=> TRUE,
				'filters' 			=> array(
					'trim'				=> array()
				),
				'rules'				=> array(
					'alpha_dash'		=> NULL
				)
			)),
			'email' 			=> new Sprig_Field_Email(array(
				'min_length' 		=> 6,
				'max_length' 		=> 64,
				'unique'			=> TRUE,
				'filters' 			=> array(
					'trim'				=> array()
				)
			)),
			'password'			=> new Sprig_Field_Password(array(
				'min_length'		=> 4,
				'max_length'		=> 16,
				'hash_with'			=> NULL,
				'filters'			=> array(
					'trim'				=> array()
				),
				'callbacks'			=> array(
					'hash_field'		=> array(
						$this, 'hash_field'
					)
				)
			)),
			'confirm_password'	=> new Sprig_Field_Password(array(
				'in_db'				=> FALSE,
				'hash_with'			=> NULL,
				'rules'				=> array(
					'matches'			=> array('password')
				)
			)),
			'date_joined'		=> new Sprig_Field_Char(array(
				'editable'			=> FALSE
			)),
			'last_login'		=> new Sprig_Field_Char(array(
				'empty'				=> TRUE,
				'null'				=> TRUE
			)),
			'active'			=> new Sprig_Field_Boolean(array(
				'default'			=> 1
			)),
			'banned'			=> new Sprig_Field_Boolean(array(
				'default'			=> 0
			))
		);
	}
	
	/**
	 * Hashes the given value
	 *
	 * @param string $value
	 * @return string
	 */
	public function hash($value)
	{
		return sha1($this->_salt . $value);
	}
	
	/**
	 * Callback for hashing fields
	 *
	 * @param Validate $validate
	 * @param string $field
	 * @return void
	 */
	public function hash_field(Validate $validate, $field)
	{
		$validate[$field] = sha1($this->_salt . $validate[$field]);
	}
	
	/**
	 * Overrides the core's create method to add routine that will
	 * add user roles this newly created user
	 *
	 * @return void
	 */
	public function create()
	{
		parent::create();
		$this->add_roles();
	}
	
	/**
	 * Add roles to the newly created user
	 * 
	 * @return void
	 */
	public function add_roles()
	{
		// get the default roles
		$config = Sprig::factory('config', array(
			'id' => Model_Config_DefaultRole::ID
		))->load();
		
		$roles = unserialize($config->content_serialized);
		if (!empty($roles) && is_array($roles))
		{
			// add roles to user
			$user_id = $this->user_id;
			foreach ($roles as $role_id)
			{
				$user_role = Sprig::factory('UserRole')
					->values(array(
						'user_id' => $user_id,
						'role_id' => $role_id
					))
					->create();
				unset($user_role);
			}
		}
	}
}