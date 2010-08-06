<?php defined('SYSPATH') or die('No direct script access.');

class Model_Mapper_PrivilegeTest extends PHPUnit_Framework_TestCase
{
	public function testObject()
	{
		$privMapper = new Model_Mapper_Privilege;
		$this->assertType('Model_Mapper_Privilege', $privMapper);
		
		return $privMapper;
	}
	
	/**
	 * @depends testObject
	 * @param Model_Mapper_Privilege $privMapper
	 */
	public function testAdd(Model_Mapper_Privilege $privMapper)
	{
		$data = array(
			'role_id' => Dc_Uuid::mint(4)->bytes,
			'resource_id' => Dc_Uuid::mint(4)->bytes,
			'privilege_name' => 'TEST_PRIVILEGE',
			'privilege_description' => 'test privilege added by unit test',
			'allow' => 1
		);
		
		$result = $privMapper->add($data);
		$this->assertTrue((boolean)$result);
		
		return array(
			'mapper' => $privMapper,
			'data' => $data
		);
	}
	
	/**
	 * @depends testAdd
	 * @param array $param
	 */
	public function testAddDuplicate(array $params)
	{
		$result = $params['mapper']->add($params['data']);
		
		$this->assertFalse($result);
		$this->assertTrue($params['mapper']->hasMessages());
		$this->assertTrue($params['mapper']->hasExceptions());
		
		$params['mapper']->reset();
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testGet(array $params)
	{
		$fromDb = $params['mapper']->get(
			$params['data']['role_id'],
			$params['data']['resource_id'],
			$params['data']['privilege_name']
		);
		
		$this->assertType('array', $fromDb);
		$this->assertEquals($fromDb['privilege_name'], $params['data']['privilege_name']);
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
	public function testGetNonExisting(array $params)
	{
		$fromDb = $params['mapper']->get(
			Dc_Uuid::mint(4)->bytes,
			Dc_Uuid::mint(4)->bytes,
			'TEST'
		);
		
		$this->assertFalse($fromDb);
		$this->assertTrue($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
		
		$params['mapper']->reset();
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testSave(array $params)
	{
		$newData = array('privilege_description' => 'new description');
		$result = $params['mapper']->save(
			$params['data']['role_id'],
			$params['data']['resource_id'],
			$params['data']['privilege_name'],
			$newData
		);
		
		$this->assertEquals(1, $result);
		
		// get it back
		$fromDb = $params['mapper']->get(
			$params['data']['role_id'],
			$params['data']['resource_id'],
			$params['data']['privilege_name']
		);
		
		$this->assertType('array', $fromDb);
		$this->assertNotEquals(
			$fromDb['privilege_description'],
			$params['data']['privilege_description']
		);
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testSaveNonExisting(array $params)
	{
		$newData = array('privilege_description' => 'New desc 123');
		$result = $params['mapper']->save(
			Dc_Uuid::mint(4)->bytes,
			Dc_Uuid::mint(4)->bytes,
			'abc',
			$newData
		);
		
		// no record affected
		$this->assertEquals(0, $result);
		
		// no failure messages
		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testDelete(array $params)
	{
		$result = $params['mapper']->delete(
			$params['data']['role_id'],
			$params['data']['resource_id'],
			$params['data']['privilege_name']
		);
		
		// one record affected
		$this->assertEquals(1, $result);
		
		// no failure messages
		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
		
		// should not exists
		$fromDb = $params['mapper']->get(
			$params['data']['role_id'],
			$params['data']['resource_id'],
			$params['data']['privilege_name']
		);
		
		// should not found a record, with message but no exceptions
		$this->assertFalse($fromDb);
		$this->assertTrue($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
		
		$params['mapper']->reset();
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testDeleteNonExisting(array $params)
	{
		$result = $params['mapper']->delete(
			Dc_Uuid::mint(4)->bytes,
			Dc_Uuid::mint(4)->bytes,
			'abc'
		);
		
		// no record affected
		$this->assertEquals(0, $result);

		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
	}
}