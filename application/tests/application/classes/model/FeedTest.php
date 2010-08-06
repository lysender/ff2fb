<?php defined('SYSPATH') or die('No direct script access.');

class Model_FeedTest extends PHPUnit_Framework_TestCase
{
	const FFUSER = 'lysender';
	
	public function testObject()
	{
		$feedModel = new Model_Feed(Dc_Uuid::mint(4)->bytes, Dc_Uuid::mint(4)->bytes);
		$this->assertType('Model_Feed', $feedModel);
		
		return $feedModel;
	}
	
	public function testLiveImport()
	{
		$batchId = Dc_Uuid::mint(4)->bytes;
		$userId = Dc_Uuid::mint(4)->bytes;
		
		$feedModel = new Model_Feed($batchId, $userId);
		$friendfeed = new Dc_Web_Friendfeed(self::FFUSER);
		$mapper = new Model_Mapper_Feed;
		
		$result = $feedModel->import($friendfeed, $mapper);
		$this->assertTrue((boolean)$result);
	}
}