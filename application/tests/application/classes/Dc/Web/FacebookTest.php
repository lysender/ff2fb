<?php defined('SYSPATH') or die('No direct script access.');

class Dc_Web_FacebookTest extends PHPUnit_Framework_TestCase
{
	public function testObject()
	{
		$fb = new Dc_Web_Facebook;
		$this->assertType('Dc_Web_Facebook', $fb);
		
		return $fb;
	}
	
	public function testConnect()
	{
		
	}
	
	public function testGetAuth()
	{
		
	}
	
	public function testPublish()
	{
		
	}
}