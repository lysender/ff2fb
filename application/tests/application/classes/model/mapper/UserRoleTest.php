<?php defined('SYSPATH') or die('No direct script access.');

class Model_Mapper_UserRoleTest extends PHPUnit_Framework_TestCase
{
	const TEST_DATA1 = 'test1';
	const TEST_DATA2 = 'test2';
	const TEST_DATA3 = 'test3';
	const TEST_DATA4 = 'test4';
	const TEST_DATA5 = 'test5';
	const TEST_DATA6 = 'test6';
	
	private static $_testUser1;
	private static $_testUser2;
	private static $_testUser3;
	
	private static $_testRole1;
	private static $_testRole2;
	private static $_testRole3;
	
	protected static $_testData = array();
	
	public function testObject()
	{
		$user = new Model_Mapper_UserRole;
		$this->assertType('Model_Mapper_UserRole', $user);
		
		// initialize test data
		for ($x=1; $x<=3; $x++)
		{
			$userVar = '_testUser' . $x;
			$roleVar = '_testRole' . $x;
			self::$$userVar = Dc_Uuid::mint(4)->bytes;
			self::$$roleVar = Dc_Uuid::mint(4)->bytes;
		}
		
		self::$_testData = array(
			// user 1 has role1, role2 and role3
			self::TEST_DATA1 => array(
				'user_id' 	=> self::$_testUser1,
				'role_id'	=> self::$_testRole1
			),
			self::TEST_DATA2 => array(
				'user_id'	=> self::$_testUser1,
				'role_id'	=> self::$_testRole2
			),
			self::TEST_DATA3 => array(
				'user_id'	=> self::$_testUser1,
				'role_id'	=> self::$_testRole3
			),
			// user 2 has only one role, role 3
			self::TEST_DATA4 => array(
				'user_id'	=> self::$_testUser2,
				'role_id'	=> self::$_testRole3
			),
			// user 3 has 2 roles, role 2 and role 3
			self::TEST_DATA5 => array(
				'user_id'	=> self::$_testUser3,
				'role_id'	=> self::$_testRole2
			),
			self::TEST_DATA6 => array(
				'user_id'	=> self::$_testUser3,
				'role_id'	=> self::$_testRole3
			)
		);
		
		return $user;
	}
	
	/**
	 * @depends testObject
	 */
	public function testAddThemAll($user)
	{
		// for user 1, with 3 roles
		$result = $user->add(self::$_testData[self::TEST_DATA1]);
		$this->assertTrue((boolean)$result);
		
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		$result = $user->add(self::$_testData[self::TEST_DATA2]);
		$this->assertTrue((boolean)$result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		$result = $user->add(self::$_testData[self::TEST_DATA3]);
		$this->assertTrue((boolean)$result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		// for user 2, with 1 role
		$result = $user->add(self::$_testData[self::TEST_DATA4]);
		$this->assertTrue((boolean)$result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		// for user 3, with 2 roles
		$result = $user->add(self::$_testData[self::TEST_DATA5]);
		$this->assertTrue((boolean)$result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		$result = $user->add(self::$_testData[self::TEST_DATA6]);
		$this->assertTrue((boolean)$result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		return $user;
	}
	
	/**
	 * @depends testAddThemAll
	 */
	public function testAddDuplicateUser1($user)
	{
		// add another user1 role 1 combination, should fail
		$data = array(
			'user_id' 	=> self::$_testUser1,
			'role_id'	=> self::$_testRole1
		);
		
		$result = $user->add($data);
		$this->assertFalse($result);
		$this->assertTrue($user->hasMessages());
		$this->assertTrue($user->hasExceptions());
		$user->reset();
		
		// add another user1 with role 2, should fail
		$data = array(
			'user_id' 	=> self::$_testUser1,
			'role_id'	=> self::$_testRole2
		);
		
		$result = $user->add($data);
		$this->assertFalse($result);
		$this->assertTrue($user->hasMessages());
		$this->assertTrue($user->hasExceptions());
		$user->reset();
	}
	
	/**
	 * @depends testAddThemAll
	 */
	public function testGetUserRole($user)
	{
		// user 1 role 1 record
		$fromDb = $user->get(self::$_testUser1, self::$_testRole1);
		
		$this->assertType('array', $fromDb);
		$this->assertEquals($fromDb['user_id'], self::$_testUser1);
		$this->assertEquals($fromDb['role_id'], self::$_testRole1);
		
		// user 1 role 3 record
		$fromDb = $user->get(self::$_testUser1, self::$_testRole3);
		
		$this->assertType('array', $fromDb);
		$this->assertEquals($fromDb['user_id'], self::$_testUser1);
		$this->assertEquals($fromDb['role_id'], self::$_testRole3);
		
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
	}
	
	/**
	 * @depends testAddThemAll
	 */
	public function testGetUserRoles($user)
	{
		// user 1 has 3 roles
		$userRoles = $user->getUserRoles(self::$_testUser1);
		$this->assertType('array', $userRoles);
		$this->assertEquals(3, count($userRoles));
		foreach ($userRoles as $ur)
		{
			$this->assertEquals($ur['user_id'], self::$_testUser1);
		}
	}
	
	/**
	 * @depends testAddThemAll
	 */
	public function testGetNonExisting($user)
	{
		// should found no data, not unless it exists in production
		$userRoles = $user->get(999, 999);
		$this->assertFalse($userRoles);
		$this->assertTrue($user->hasMessages());
		$user->reset();
		
		$userRoles = $user->getUserRoles(999);
		$this->assertFalse($userRoles);
		$this->assertTrue($user->hasMessages());
		$user->reset();
	}
	
	/**
	 * @depends testAddThemAll
	 */
	public function testUpdateUserRole($user)
	{
		// update user 2 who has role 3, change role to 2
		$data = array('role_id' => self::$_testRole2);
		$result = $user->save(self::$_testUser2, self::$_testRole3, $data);
		
		// should affect one record
		$this->assertEquals(1, $result);
		
		// get it
		$fromDb = $user->get(self::$_testUser2, $data['role_id']);
		$this->assertType('array', $fromDb);
		
		// compare
		$this->assertEquals($fromDb['user_id'], self::$_testUser2);
		$this->assertEquals($fromDb['role_id'], $data['role_id']);
		
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
	}
	
	/**
	 * @depends testAddThemAll
	 */
	public function testUpdateNonExisting($user)
	{
		$data = array('role_id' => 999);
		$result = $user->save(999, 999, $data);
		
		$this->assertEquals(0, $result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
	}
	
	/**
	 * @depends testAddThemAll
	 */
	public function testDeleteAll($user)
	{
		// only user2 is updated to role 2 so the others is same as declared by default
		// delete user 2 roles one by one which has 2 roles
		$result = $user->delete(
			self::$_testData[self::TEST_DATA5]['user_id'],
			self::$_testData[self::TEST_DATA5]['role_id']
		);
		// one record deleted
		$this->assertEquals(1, $result);
		
		$result = $user->delete(
			self::$_testData[self::TEST_DATA6]['user_id'],
			self::$_testData[self::TEST_DATA6]['role_id']
		);
		// one record deleted
		$this->assertEquals(1, $result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		// now lets delete user 1 who has 3 roles, delete them all
		$result = $user->delete(self::$_testUser1);
		
		// 3 records deleted
		$this->assertEquals(3, $result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		// delete user 2
		$result = $user->delete(self::$_testUser2, self::$_testRole2);
		// one record deleted
		$this->assertEquals(1, $result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		return $user;
	}
	
	/**
	 * @depends testDeleteAll
	 */
	public function testGetAfterDelete($user)
	{
		// already deleted
		$fromDb = $user->get(
			self::$_testData[self::TEST_DATA5]['user_id'],
			self::$_testData[self::TEST_DATA5]['role_id']
		);
		$this->assertFalse($fromDb);
		$this->assertTrue($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		$user->reset();
		
		// already deleted
		$fromDb = $user->get(
			self::$_testData[self::TEST_DATA6]['user_id'],
			self::$_testData[self::TEST_DATA6]['role_id']
		);
		$this->assertFalse($fromDb);
		$this->assertTrue($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		$user->reset();
		
		// already deleted
		$userRoles = $user->getUserRoles(self::$_testUser1);
		$this->assertFalse($userRoles);
		$this->assertTrue($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		$user->reset();
	}
}