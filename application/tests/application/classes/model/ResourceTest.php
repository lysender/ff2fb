<?php defined('SYSPATH') or die('No direct script access.');

class Model_ResourceTest extends PHPUnit_Framework_TestCase
{	
	public function testObject()
	{
		$resource = new Model_Resource(Dc_Uuid::mint(4)->bytes);
		$this->assertType('Model_Resource', $resource);
		
		return $resource;
	}
	
	/**
	 * @expectedException Exception
	 */
	public function testInvalidId()
	{
		$resource = new Model_Resource;
	}
	
	public function testAdd()
	{
		$id = Dc_Uuid::mint(4)->string;
		$resource = new Model_Resource($id);
		$resource->resourceName = 'testResource001';
		$resource->resourceDescription = 'testResource001 description...';
		
		$mapper = new Model_Mapper_Resource;
		$result = $resource->add($mapper);
		$this->assertTrue($result);
		
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
		
		return $resource;
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGet($prevResource)
	{
		$prevData = $prevResource->toArray(true);
		
		$resource = new Model_Resource($prevData['resource_id']);
		$mapper = new Model_Mapper_Resource;
		
		$this->assertTrue($resource->get($mapper));
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
		
		$this->assertEquals($prevData['resource_name'], $resource->resourceName);
		$this->assertEquals($prevData['resource_description'], $resource->resourceDescription);
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGetNonExisting($prevResource)
	{
		$prevData = $prevResource->toArray(true);
		
		$resource = new Model_Resource(Dc_Uuid::mint(4)->string);
		$mapper = new Model_Mapper_Resource;
		
		$this->assertFalse($resource->get($mapper));
		$this->assertTrue($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 */
	public function testSave($prevResource)
	{
		$prevData = $prevResource->toArray(true);
		
		$resource = new Model_Resource($prevData['resource_id']);
		$resource->resourceName = 'testResource001-Edit';
		$resource->resourceDescription = 'testResource001-Edit description';
		
		$mapper = new Model_Mapper_Resource;
		$this->assertTrue($resource->save($mapper));
		
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
		
		// get it again
		$resource2 = new Model_Resource($prevData['resource_id']);
		$this->assertTrue($resource2->get($mapper));
		
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
		
		// compare with previous
		$this->assertNotEquals($prevData['resource_name'], $resource2->resourceName);
		$this->assertNotEquals($prevData['resource_description'], $resource2->resourceDescription);
		
		// same with edited
		$this->assertEquals($resource->resourceName, $resource2->resourceName);
		$this->assertEquals($resource->resourceDescription, $resource2->resourceDescription);
		
		return $resource2;
	}
	
	/**
	 * @depends testSave
	 */
	public function testDelete($prevResource)
	{
		$prevData = $prevResource->toArray(true);
		$resource = new Model_Resource($prevData['resource_id']);
		
		$mapper = new Model_Mapper_Resource;
		$this->assertTrue($resource->delete($mapper));
		
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
	}
}