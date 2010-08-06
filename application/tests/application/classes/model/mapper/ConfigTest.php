<?php defined('SYSPATH') or die('No direct script access.');

class Model_Mapper_ConfigTest extends PHPUnit_Framework_TestCase
{
	public function testObject()
	{
		$config = new Model_Mapper_Config;
		$this->assertType('Model_Mapper_Config', $config);
	}
	
	public function testGet()
	{
		$config = new Model_Mapper_Config;
		$data = $config->get(Model_Mapper_Config::DEFAULT_CONFIG_ID);
		
		$this->assertType('array', $data);
		$this->assertEquals($data['id'], Model_Mapper_Config::DEFAULT_CONFIG_ID);
		
		return $data;
	}
	
	/**
	 * @depends testGet
	 */
	public function testSave(array $data)
	{
		$config = new Model_Mapper_Config;
		$newConfig = array(
			'default_user_role' => array(
				'yaydada sd asda sa'
			),
			'application_enabled' => true,
			'foo' => 'bar',
			'test' => 1000
		);
		
		$newData = array(
			'date_modified' => date('Y-m-d H:i:s'),
			'content_serialized' => serialize($newConfig)
		);
		
		$result = $config->save(Model_Mapper_Config::DEFAULT_CONFIG_ID, $newData);
		$this->assertEquals(1, $result);
		
		// restore old content
		$result = $config->save(Model_Mapper_Config::DEFAULT_CONFIG_ID, $data);
		$this->assertEquals(1, $result);
	}
}