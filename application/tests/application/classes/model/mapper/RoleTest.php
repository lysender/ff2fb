<?php defined('SYSPATH') or die('No direct script access.');

class Model_Mapper_RoleTest extends PHPUnit_Framework_TestCase
{
	public function testObject()
	{
		$roleMapper = new Model_Mapper_Role;
		$this->assertType('Model_Mapper_Role', $roleMapper);
		
		return $roleMapper;
	}
	
	/**
	 * @depends testObject
	 * @param $roleMapper
	 */
	public function testAdd($roleMapper)
	{
		$data = array(
			'role_id' => Dc_Uuid::mint(4)->bytes,
			'role_name' => 'testAddRole',
			'role_description' => 'added by unit testing'
		);
		$result = $roleMapper->add($data);
		$this->assertTrue((boolean)$result);
		
		return array(
			'mapper' => $roleMapper,
			'data' => $data
		);
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testAddDuplicate(array $params)
	{
		$result = $params['mapper']->add($params['data']);
		$this->assertFalse((boolean)$result);
		$this->assertTrue($params['mapper']->hasExceptions());
		$this->assertTrue($params['mapper']->hasMessages());
		
		$params['mapper']->reset();
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testGet(array $params)
	{
		$roleId = $params['data']['role_id'];
		$fromDb = $params['mapper']->get($roleId);
		$this->assertType('array', $fromDb);
		$this->assertEquals($fromDb['role_name'], $params['data']['role_name']);
		
		return $params;
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testGetAll(array $params)
	{
		$result = $params['mapper']->getAll();
		$this->assertType('array', $result);
		
		$this->assertFalse($params['mapper']->hasExceptions());
		$this->assertFalse($params['mapper']->hasMessages());
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testGetNotExisting(array $params)
	{
		$roleId = Dc_Uuid::mint(4)->bytes;
		$fromDb = $params['mapper']->get($roleId);
		$this->assertFalse($fromDb);
		$this->assertTrue($params['mapper']->hasMessages());
		
		$params['mapper']->reset();
	}
	
	/**
	 * @depends testGet
	 * @param array $params
	 */
	public function testSave(array $params)
	{
		$roleId = $params['data']['role_id'];
		$updatedData = $params['data'];
		$updatedData['role_name'] = 'testAddRoleUp';
		
		$result = $params['mapper']->save($roleId, $updatedData);
		$this->assertTrue((boolean)$result);
		
		// test updated name
		$getData = $params['mapper']->get($roleId);
		$this->assertType('array', $getData);
		
		$this->assertEquals($updatedData['role_name'], $getData['role_name']);
	}
	
	/**
	 * @depends testGet
	 * @param array $params
	 */
	public function testSaveNonExisting(array $params)
	{
		$roleId = Dc_Uuid::mint(4)->bytes;
		$updatedData = array('role_name' => 'NewName');
		
		$result = $params['mapper']->save($roleId, $updatedData);
		
		// affected rows must be 0 with no failure messages
		
		$this->assertEquals(0, $result);
		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
	}
	
	/**
	 * @depends testGet
	 * @param array $params
	 */
	public function testDelete(array $params)
	{
		$roleId = $params['data']['role_id'];
		$result = $params['mapper']->delete($roleId);
		$this->assertTrue((boolean)$result);
		
		// get it
		$fromDb = $params['mapper']->get($roleId);
		$this->assertFalse($fromDb);
		$this->assertTrue($params['mapper']->hasMessages());
		$params['mapper']->reset();
	}
	
	/**
	 * @depends testGet
	 * @param array $params
	 */
	public function testDeleteNonExisting(array $params)
	{
		$roleId = Dc_Uuid::mint(4)->bytes;
		$result = $params['mapper']->delete($roleId);
		
		$this->assertEquals(0, $result);
		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
	}
}