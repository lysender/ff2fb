<?php defined('SYSPATH') or die('No direct script access.');

class Model_UserTest extends PHPUnit_Framework_TestCase
{
	protected $_usernameFails = array(
		'',
		'short',
		'looooooooooooooooooooooooooooooooooooooooooooooong',
		'DSR@#$V!@#!CC@#',
		'a lal lal lal'
	);
	
	protected $_passwordFails = array(
		'short',
		'looooooooooooooooooooooooooooooooooooooooooooooong'
	);
	
	protected $_emailFails = array(
		'invalidEmail',
		'So@many@symbols.com',
		'$#%#asda@yahoo.net',
		'admin@careers.zoooooo'
	);
	
	public function testObject()
	{
		$user = new Model_User;
		$this->assertType('Model_User', $user);
	}
	
	/**
	 * @depends testObject
	 */
	public function testHash()
	{
		$user = new Model_User;
		$password = 'pass';
		
		$this->assertNotEquals($password, $user->hash($password));
		$this->assertEquals(40, strlen($user->hash($password)));
	}
	
	/**
	 * @depends testObject
	 */
	public function testUserMapper()
	{
		$user = new Model_User;
		$userMapper = $this->getMock('Model_Mapper_User');
		
		$result = $user->setUserMapper($userMapper);
		$this->assertEquals($userMapper, $user->getUserMapper());
	}
	
	/**
	 * @depends testObject
	 */
	public function testUserRoleMapper()
	{
		$user = new Model_User;
		$userRoleMapper = $this->getMock('Model_Mapper_UserRole');
		
		$result = $user->setUserRoleMapper($userRoleMapper);
		$this->assertEquals($userRoleMapper, $user->getUserRoleMapper());
	}
	
	/**
	 * @depends testObject
	 */
	public function testRules()
	{
		$user = new Model_User;
		$rules = array(
			'field1' => array(
				'rule1' => array('param1', 'param2')
			)
		);
		
		$user->setRules($rules);
		$this->assertEquals($rules, $user->getRules());
	}
	
	/**
	 * @depends testObject
	 */
	public function testCheckRegData()
	{
		// using default rules
		$data = array(
			'username' => 'testUser009',
			'password' => 'testPassword009',
			'confirm_password' => 'testPassword009',
			'email' => 'testmail@testdomain.org'
		);
		
		$user = new Model_User;
		$check = $user->checkRegData($data);
		$this->assertTrue($check);
		
		// test password mismatch
		$data['confirm_password'] = '@$%#^$%';
		$this->assertType('array', $user->checkRegData($data));
	}
	
	/**
	 * @depends testObject
	 */
	public function testFailData()
	{
		$tmp = array(
			'username' => 'testUser009',
			'password' => 'testPassword009',
			'confirm_password' => 'testPassword009',
			'email' => 'testmail@testdomain.org'
		);
		
		$user = new Model_User;
		
		// test fail usernames
		foreach ($this->_usernameFails as $username)
		{
			$data = $tmp;
			$data['username'] = $username;
			
			$this->assertNotEquals(true, $user->checkRegData($data));
		}
		
		// test fail passwords
		foreach ($this->_passwordFails as $password)
		{
			$data = $tmp;
			$data['password'] = $password;
			
			$this->assertNotEquals(true, $user->checkRegData($data));
		}
		
		// test fail emails
		foreach ($this->_emailFails as $email)
		{
			$data = $tmp;
			$data['email'] = $email;
			
			$this->assertNotEquals(true, $user->checkRegData($data));
		}
	}
	
	/**
	 * @depends testObject
	 */
	public function testCustomRules()
	{
		$rules = array(
			'username' => array(
				'exact_length' => array(10),
				'not_empty' => null,
				'alpha' => null
			)
		);
		
		// passed
		$data = array(
			'username' => 'rootrootro'
		);
		$user = new Model_User;
		$user->setRules($rules);
		$this->assertTrue($user->checkRegData($data));
		
		// fails
		$data['username'] = 'abcd';
		$this->assertNotEquals(true, $user->checkRegData($data));
	}
	
	public function testCreateDefaultRole()
	{
		$mapper = new Model_Mapper_Role;
		
		$data = $mapper->getAll();
		foreach ($data as $role)
		{
			$id = Dc_Uuid::import($role['role_id'])->string;
			echo "$id\n";
			echo $role['role_name'];
		}
	}
}