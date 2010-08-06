<?php defined('SYSPATH') or die('No direct script access.');

class Model_Mapper_JobTest extends PHPUnit_Framework_TestCase
{	
	public function testObject()
	{
		$mapper = new Model_Mapper_Job;
		$this->assertType('Model_Mapper_Job', $mapper);
	}
	
	public function testAdd()
	{
		$mapper = new Model_Mapper_Job;
		$data = array(
			'job_id'	=> Dc_Uuid::mint(4)->bytes,
			'user_id' 	=> Dc_Uuid::mint(4)->bytes,
			'content_serialized' => 'abc'
		);
		
		$result = $mapper->add($data);
		$this->assertTrue((boolean)$result);
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
		
		return $data;
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGet(array $data)
	{
		$mapper = new Model_Mapper_Job;
		$fromDb = $mapper->get($data['job_id']);
		$this->assertEquals($data['content_serialized'], $fromDb['content_serialized']);
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGetNonExisting()
	{
		$jobId = Dc_Uuid::mint(4)->bytes;
		$mapper = new Model_Mapper_Job;
		$result = $mapper->get($jobId);
		
		$this->assertFalse($result);
		$this->assertTrue($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGetByUser(array $data)
	{
		$userId = $data['user_id'];
		$mapper = new Model_Mapper_Job;
		
		// By usre id
		$records = $mapper->getByUser($userId);
		$this->assertTrue((boolean)$records);
		
		foreach ($records as $job)
		{
			$this->assertEquals($job['user_id'], $data['user_id']);
			$this->assertType('array', $job);
		}
		
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGetByUserNonExisting()
	{
		$userId = Dc_Uuid::mint(4)->bytes;
		
		$mapper = new Model_Mapper_Job;
		$result = $mapper->getByUser($userId);
		$this->assertFalse($result);
		$this->assertTrue($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
	}
}