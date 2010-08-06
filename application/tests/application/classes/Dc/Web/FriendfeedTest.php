<?php defined('SYSPATH') or die('No direct script access.');

class Dc_Web_FriendfeedTest extends PHPUnit_Framework_TestCase
{
	const FFUSER = 'lysender';
	
	public function testObject()
	{
		$ff = new Dc_Web_Friendfeed(self::FFUSER);
		$this->assertType('Dc_Web_Friendfeed', $ff);
		
		return $ff;
	}
	
	public function testGetFeeds()
	{
		$ff = new Dc_Web_Friendfeed(self::FFUSER);
		$feeds = $ff->getFeeds();
		
		$this->assertTrue(!empty($feeds));
		
		// check individual entries
		foreach ($feeds as $entry)
		{
			$this->assertTrue((boolean)$entry['feed_id']);
			$this->assertEquals(36, strlen($entry['feed_id']));
			$this->assertTrue((boolean)$entry['body']);
			$this->assertTrue((boolean)$entry['url']);
			$this->assertTrue((boolean)$entry['date']);
			$this->assertTrue((boolean)$entry['from']);
			if (isset($entry['thumbnail']))
			{
				$this->assertType('stdClass', $entry['thumbnail']);
			}
			$this->assertTrue($entry['comments'] >= 0);
			$this->assertTrue($entry['likes'] >= 0);
		}
	}
	
	public function testGetLikes()
	{
		$ff = new Dc_Web_Friendfeed(self::FFUSER);
		$feeds = $ff->getLikes();
		
		$this->assertTrue(!empty($feeds));
		
		// check individual entries
		foreach ($feeds as $entry)
		{
			$this->assertTrue((boolean)$entry['feed_id']);
			$this->assertEquals(36, strlen($entry['feed_id']));
			$this->assertTrue((boolean)$entry['body']);
			$this->assertTrue((boolean)$entry['url']);
			$this->assertTrue((boolean)$entry['date']);
			$this->assertTrue((boolean)$entry['from']);
			if (isset($entry['thumbnail']))
			{
				$this->assertType('stdClass', $entry['thumbnail']);
			}
			$this->assertTrue($entry['comments'] >= 0);
			$this->assertTrue($entry['likes'] >= 0);
		}
	}
	
	public function testImport()
	{
		$ff = new Dc_Web_Friendfeed(self::FFUSER);
		$feeds = $ff->import()->feeds;
		
		$this->assertTrue((boolean)$feeds);
		foreach ($feeds as $entry)
		{
			$this->assertTrue((boolean)$entry['feed_id']);
			$this->assertEquals(36, strlen($entry['feed_id']));
			$this->assertTrue((boolean)$entry['body']);
			$this->assertTrue((boolean)$entry['url']);
			$this->assertTrue((boolean)$entry['date']);
			$this->assertTrue((boolean)$entry['from']);
			if (isset($entry['thumbnail']))
			{
				$this->assertType('stdClass', $entry['thumbnail']);
			}
			$this->assertTrue($entry['comments'] >= 0);
			$this->assertTrue($entry['likes'] >= 0);
		}
	}
	
	public function testGetFeedFailed()
	{
		$ff = new Dc_Web_Friendfeed('nonExistingUser');
		$this->assertFalse($ff->getFeeds());
		$this->assertFalse($ff->getLikes());
		$this->assertTrue(empty($ff->import()->feeds));
	}
}