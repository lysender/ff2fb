<?php defined('SYSPATH') or die('No direct script access.');

class Model_Mapper_ResourceTest extends PHPUnit_Framework_TestCase
{
	public function testObject()
	{
		$resourceMapper = new Model_Mapper_Resource;
		$this->assertType('Model_Mapper_Resource', $resourceMapper);
		
		return $resourceMapper;
	}
	
	/**
	 * @depends testObject
	 * @param $resourceMapper
	 */
	public function testAdd($resourceMapper)
	{
		$data = array(
			'resource_id' => Dc_Uuid::mint(4)->bytes,
			'resource_name' => 'testAddResource',
			'resource_description' => 'added by unit testing'
		);
		$result = $resourceMapper->add($data);
		$this->assertTrue((boolean)$result);
		
		return array(
			'mapper' => $resourceMapper,
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
		$resourceId = $params['data']['resource_id'];
		$fromDb = $params['mapper']->get($resourceId);
		$this->assertType('array', $fromDb);
		$this->assertEquals($fromDb['resource_name'], $params['data']['resource_name']);
		
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
		$resourceId = Dc_Uuid::mint(4)->bytes;
		$fromDb = $params['mapper']->get($resourceId);
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
		$resourceId = $params['data']['resource_id'];
		$updatedData = $params['data'];
		$updatedData['resource_name'] = 'testAddResourceUp';
		
		$result = $params['mapper']->save($resourceId, $updatedData);
		$this->assertTrue((boolean)$result);
		
		// test updated name
		$getData = $params['mapper']->get($resourceId);
		$this->assertType('array', $getData);
		
		$this->assertEquals($updatedData['resource_name'], $getData['resource_name']);
	}
	
	/**
	 * @depends testGet
	 * @param array $params
	 */
	public function testSaveNonExisting(array $params)
	{
		$resourceId = Dc_Uuid::mint(4)->bytes;
		$updatedData = array('resource_name' => 'NewName');
		
		$result = $params['mapper']->save($resourceId, $updatedData);
		
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
		$resourceId = $params['data']['resource_id'];
		$result = $params['mapper']->delete($resourceId);
		$this->assertTrue((boolean)$result);
		
		// get it
		$fromDb = $params['mapper']->get($resourceId);
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
		$resourceId = Dc_Uuid::mint(4)->bytes;
		$result = $params['mapper']->delete($resourceId);
		
		$this->assertEquals(0, $result);
		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
	}
}