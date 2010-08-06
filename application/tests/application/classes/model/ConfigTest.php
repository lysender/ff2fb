<?php defined('SYSPATH') or die('No direct script access.');

class Model_ConfigTest extends PHPUnit_Framework_TestCase
{
	public function testObject()
	{
		$config = new Model_Config(Model_Config::CONFIG_GENERAL);
		$this->assertType('Model_Config', $config);
	}
	
	public function testGet()
	{
		$mapper = new Model_Mapper_Config;
		$config = new Model_Config(Model_Config::CONFIG_GENERAL);
		
		$this->assertTrue($config->get($mapper));
		$data = $config->toArray();
		$this->assertTrue(!empty($data));
		
		return $data;
	}
	
	/**
	 * @depends testGet
	 */
	public function testSave(array $data)
	{
		$mapper = new Model_Mapper_Config;
		$config = new Model_Config(Model_Config::CONFIG_GENERAL);
		
		$config->description = 'Edited description ne...';
		$config->content = array('default_user_role' => array('again' => 1, 'xyz' => 'bbbb'));
		
		$this->assertEquals(1, $config->save($mapper));
		
		// restore old settings
		$config->description = $data['description'];
		$config->content = $data['content_serialized'];
		$this->assertEquals(1, $config->save($mapper));
	}
}