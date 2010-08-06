<?php defined('SYSPATH') or die('No direct script access.');

class Model_Mapper_UserTest extends PHPUnit_Framework_TestCase
{
	public function testObject()
	{
		$user = new Model_Mapper_User;
		$this->assertType('Model_Mapper_User', $user);
		
		return $user;
	}
	
	/**
	 * @depends testObject
	 */
	public function testAdd($user)
	{
		$data = array(
			'user_id' 	=> Dc_Uuid::mint(4)->bytes,
			'username'	=> 'testUser001',
			'email'		=> 'testUser001@test.com',
			'password'	=> 'testUser001',
			'date_joined' => date('Y-m-d H:i:s'),
			'active'	=> 1,
			'banned'	=> 0
		);
		
		$result = $user->add($data);
		$this->assertTrue((boolean)$result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		return $data;
	}
	
	/**
	 * @depends testAdd
	 */
	public function testAddDuplicateId(array $data)
	{
		$newData = $data;
		// change username, email and password except id
		$newData['username'] = 'testUser002';
		$newData['email'] = 'testUser002@test.com';
		$newData['password'] = 'testUser002';
		
		$user = new Model_Mapper_User;
		$result = $user->add($newData);
		$this->assertFalse($result);
		$this->assertTrue($user->hasMessages());
		$this->assertTrue($user->hasExceptions());
		
		$messages = $user->getMessages();
		$exps = $user->getExceptions();
		
		$this->assertEquals(1, count($messages));
		$this->assertEquals(1, count($exps));
	}
	
	/**
	 * @depends testAdd
	 */
	public function testAddDuplicateUsername(array $data)
	{
		$newData = $data;
		// change user_id, email and password except username
		$newData['user_id'] = Dc_Uuid::mint(4)->bytes;
		$newData['email'] = 'testUser002@test.com';
		$newData['password'] = 'testUser002';
		
		$user = new Model_Mapper_User;
		$result = $user->add($newData);
		$this->assertFalse($result);
		$this->assertTrue($user->hasMessages());
		$this->assertTrue($user->hasExceptions());
		
		$messages = $user->getMessages();
		$exps = $user->getExceptions();
		
		$this->assertEquals(1, count($messages));
		$this->assertEquals(1, count($exps));
	}
	
	/**
	 * @depends testAdd
	 */
	public function testAddDuplicateEmail(array $data)
	{
		$newData = $data;
		// change user_id, username and password except email
		$newData['user_id'] = Dc_Uuid::mint(4)->bytes;
		$newData['username'] = 'testUser002';
		$newData['password'] = 'testUser002';
		
		$user = new Model_Mapper_User;
		$result = $user->add($newData);
		$this->assertFalse($result);
		$this->assertTrue($user->hasMessages());
		$this->assertTrue($user->hasExceptions());
		
		$messages = $user->getMessages();
		$exps = $user->getExceptions();
		
		$this->assertEquals(1, count($messages));
		$this->assertEquals(1, count($exps));
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGetById(array $data)
	{
		$user = new Model_Mapper_User;
		$id = $data['user_id'];
		
		$result = $user->getById($id);
		$messages = $user->getMessages();
		$this->assertType('array', $result);
		
		$this->assertEquals($data['username'], $result['username']);
		$this->assertEquals($data['date_joined'], $result['date_joined']);
		$this->assertEquals($data['password'], $result['password']);
		
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGetByIdNotExisting()
	{
		$user = new Model_Mapper_User;
		$id = Dc_Uuid::mint(4)->bytes;
		
		$result = $user->getById($id);
		$this->assertFalse($result);
		
		$this->assertTrue($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGetByUsername(array $data)
	{
		$user = new Model_Mapper_User;
		$username = $data['username'];
		
		$result = $user->getByUsername($username);
		$messages = $user->getMessages();
		$this->assertType('array', $result);
		
		$this->assertEquals($data['username'], $result['username']);
		$this->assertEquals($data['date_joined'], $result['date_joined']);
		
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGetByUsernameNotExisting()
	{
		$user = new Model_Mapper_User;
		$username = 'someNonExisting';
		
		$result = $user->getByUsername($username);
		$this->assertFalse($result);
		
		$this->assertTrue($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGetByEmail(array $data)
	{
		$user = new Model_Mapper_User;
		$email = $data['email'];
		
		$result = $user->getByEmail($email);
		$messages = $user->getMessages();
		$this->assertType('array', $result);
		
		$this->assertEquals($data['username'], $result['username']);
		$this->assertEquals($data['date_joined'], $result['date_joined']);
		
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGetByEmailNotExisting()
	{
		$user = new Model_Mapper_User;
		$email = 'someNonExisting@mail.com';
		
		$result = $user->getByEmail($email);
		$this->assertFalse($result);
		
		$this->assertTrue($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 */
	public function testSave(array $data)
	{
		$id = $data['user_id'];
		
		// update password
		$newData = array('password' => 'newPassword002');
		
		$user = new Model_Mapper_User;
		$result = $user->save($id, $newData);
		
		// should save successfully
		$this->assertTrue((boolean)$result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		// get it back
		$fromDb = $user->getById($id);
		$this->assertTrue((boolean)$fromDb);
		
		// should have different password now compared to old
		$this->assertNotEquals($data['password'], $fromDb['password']);
		// should be equal to the new password
		$this->assertEquals($newData['password'], $fromDb['password']);
		
		return $fromDb;
	}
	
	/**
	 * @depends testAdd
	 */
	public function testSaveNonExisting()
	{
		$user = new Model_Mapper_User;
		$id = Dc_Uuid::mint(4)->bytes;
		
		$data = array('password' => 'testPassword');
		$result = $user->save($id, $data);
		
		$this->assertEquals(0, $result);
	}
	
	/**
	 * @depends testSave
	 */
	public function testDelete(array $data)
	{
		$id = $data['user_id'];
		
		$user = new Model_Mapper_User;
		$result = $user->delete($id);
		
		$this->assertEquals(1, $result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		// get it back
		$fromDb = $user->getById($id);
		$this->assertFalse($fromDb);
		$this->assertTrue($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
	}
	
	/**
	 * @depends testSave
	 */
	public function testDeleteNonExisting()
	{
		$user = new Model_Mapper_User;
		$id = Dc_Uuid::mint(4)->bytes;
		
		$result = $user->delete($id);
		
		$this->assertEquals(0, $result);
	}
}