<?php defined('SYSPATH') or die('No direct script access.');

class Model_RoleTest extends PHPUnit_Framework_TestCase
{	
	public function testObject()
	{
		$role = new Model_Role(Dc_Uuid::mint(4)->bytes);
		$this->assertType('Model_Role', $role);
		
		return $role;
	}
	
	/**
	 * @expectedException Exception
	 */
	public function testInvalidId()
	{
		$role = new Model_Role;
	}
	
	public function testAdd()
	{
		$id = Dc_Uuid::mint(4)->string;
		$role = new Model_Role($id);
		$role->roleName = 'testRole001';
		$role->roleDescription = 'testRole001 description...';
		
		$mapper = new Model_Mapper_Role;
		$result = $role->add($mapper);
		$this->assertTrue($result);
		
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
		
		return $role;
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGet($prevRole)
	{
		$prevData = $prevRole->toArray(true);
		
		$role = new Model_Role($prevData['role_id']);
		$mapper = new Model_Mapper_Role;
		
		$this->assertTrue($role->get($mapper));
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
		
		$this->assertEquals($prevData['role_name'], $role->roleName);
		$this->assertEquals($prevData['role_description'], $role->roleDescription);
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGetNonExisting($prevRole)
	{
		$prevData = $prevRole->toArray(true);
		
		$role = new Model_Role(Dc_Uuid::mint(4)->string);
		$mapper = new Model_Mapper_Role;
		
		$this->assertFalse($role->get($mapper));
		$this->assertTrue($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 */
	public function testSave($prevRole)
	{
		$prevData = $prevRole->toArray(true);
		
		$role = new Model_Role($prevData['role_id']);
		$role->roleName = 'testRole001-Edit';
		$role->roleDescription = 'testRole001-Edit description';
		
		$mapper = new Model_Mapper_Role;
		$this->assertTrue($role->save($mapper));
		
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
		
		// get it again
		$role2 = new Model_Role($prevData['role_id']);
		$this->assertTrue($role2->get($mapper));
		
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
		
		// compare with previous
		$this->assertNotEquals($prevData['role_name'], $role2->roleName);
		$this->assertNotEquals($prevData['role_description'], $role2->roleDescription);
		
		// same with edited
		$this->assertEquals($role->roleName, $role2->roleName);
		$this->assertEquals($role->roleDescription, $role2->roleDescription);
		
		return $role2;
	}
	
	/**
	 * @depends testSave
	 */
	public function testDelete($prevRole)
	{
		$prevData = $prevRole->toArray(true);
		$role = new Model_Role($prevData['role_id']);
		
		$mapper = new Model_Mapper_Role;
		$this->assertTrue($role->delete($mapper));
		
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
	}
}