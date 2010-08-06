<?php defined('SYSPATH') or die('No direct script access.');

class Model_Mapper_FeedTest extends PHPUnit_Framework_TestCase
{
	const FFUSER = 'lysender';
	
	public function testObject()
	{
		$feedMapper = new Model_Mapper_Feed;
		$this->assertType('Model_Mapper_Feed', $feedMapper);
	}
	
	public function testAdd()
	{
		$mapper = new Model_Mapper_Feed;
		$data = array(
			'feed_id' 	=> Dc_Uuid::mint(4)->bytes,
			'batch_id'	=> Dc_Uuid::mint(4)->bytes,
			'user_id' 	=> Dc_Uuid::mint(4)->bytes,
			'content_serialized' => 'abc',
			'scheduled' => 0
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
		$mapper = new Model_Mapper_Feed;
		$fromDb = $mapper->get($data['feed_id']);
		$this->assertEquals($data['content_serialized'], $fromDb['content_serialized']);
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 */
	public function testFeedExists(array $data)
	{
		$mapper = new Model_Mapper_Feed;
		$this->assertTrue($mapper->feedExists($data['feed_id']));
		
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGetNonExisting()
	{
		$feedId = Dc_Uuid::mint(4)->bytes;
		$mapper = new Model_Mapper_Feed;
		$result = $mapper->get($feedId);
		
		$this->assertFalse($result);
		$this->assertTrue($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGetByBatch(array $data)
	{
		$batchId = $data['batch_id'];
		$userId = $data['user_id'];
		$mapper = new Model_Mapper_Feed;
		
		// By batch id only
		$records = $mapper->getByBatch($batchId);
		$this->assertTrue((boolean)$records);
		
		foreach ($records as $feed)
		{
			$this->assertEquals($feed['batch_id'], $data['batch_id']);
			$this->assertType('array', $feed);
		}
		
		// By batch id and user id
		$records = $mapper->getByBatch($batchId, $userId);
		$this->assertTrue((boolean)$records);
		
		foreach ($records as $feed)
		{
			$this->assertEquals($feed['batch_id'], $data['batch_id']);
			$this->assertEquals($feed['user_id'], $data['user_id']);
			$this->assertType('array', $feed);
		}
		
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGetByBatchNonExisting()
	{
		$batchId = Dc_Uuid::mint(4)->bytes;
		$userId = Dc_Uuid::mint(4)->bytes;
		
		$mapper = new Model_Mapper_Feed;
		$result = $mapper->getByBatch($batchId);
		$this->assertFalse($result);
		$this->assertTrue($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
		
		$result = $mapper->getByBatch($batchId, $userId);
		$this->assertFalse($result);
		$this->assertTrue($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
	}
}