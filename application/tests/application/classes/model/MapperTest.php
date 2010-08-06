<?php defined('SYSPATH') or die('No direct script access.');

class Model_MapperTest extends PHPUnit_Framework_TestCase
{
	public function testObject()
	{
		$mapper = $this->getMock(
			'Model_Mapper',
			array(
				'insert',
				'update',
				'deleteRecord',
				'fetchRow',
				'fetchAll'
			)
		);
		
		$this->assertType('Model_Mapper', $mapper);
		return $mapper;
	}
	
	/**
	 * @depends testObject
	 * @param $mapper
	 */
	public function testExceptions($mapper)
	{
		$e = new Exception('Test');
		
		$mapper->addException($e);
		$this->assertTrue($mapper->hasExceptions());
		$this->assertEquals(1, count($mapper->getExceptions()));
		$exceptions = $mapper->getExceptions();
		$exp = reset($exceptions);
		$this->assertEquals($e, $exp);
		
		// add another
		$e2 = new Exception('Test 2');
		$mapper->addException($e2);
		$this->assertTrue($mapper->hasExceptions());
		$this->assertEquals(2, count($mapper->getExceptions()));
		
		// clear
		$mapper->clearExceptions();
		$this->assertFalse($mapper->hasExceptions());
		$this->assertEquals(0, count($mapper->getExceptions()));
	}
	
	/**
	 * @depends testObject
	 * @param $mapper
	 */
	public function testMessages($mapper)
	{
		// message only
		$msg1 = 'test 1';
		$mapper->addMessage($msg1);
		$this->assertTrue($mapper->hasMessages());
		
		$msgs = $mapper->getMessages();
		$this->assertEquals(1, count($msgs));
		$msg = reset($msgs);
		$this->assertEquals($msg1, $msg);
		
		// should have no exceptions
		$this->assertFalse($mapper->hasExceptions());
		
		// add another
		$msg2 = 'test 2';
		$mapper->addMessage($msg2);
		$this->assertTrue($mapper->hasMessages());
		$this->assertEquals(2, count($mapper->getMessages()));
		
		// still no exception
		$this->assertFalse($mapper->hasExceptions());
		
		// clear
		$mapper->clearMessages();
		$this->assertFalse($mapper->hasMessages());
	}
	
	/**
	 * @depends testObject
	 * @param $mapper
	 */
	public function testMessagesExceptions($mapper)
	{
		$msg1 = 'test 1';
		$exp1 = new Exception('test 1');
		$mapper->addMessage($msg1, $exp1);
		
		$this->assertTrue($mapper->hasMessages());
		$this->assertTrue($mapper->hasExceptions());
		
		// count it
		$this->assertEquals(1, count($mapper->getMessages()));
		$this->assertEquals(1, count($mapper->getExceptions()));
		
		// match it
		$exceptions = $mapper->getExceptions();
		$msgs = $mapper->getMessages();
		
		$exp = reset($exceptions);
		$msg = reset($msgs);
		
		$this->assertEquals($msg1, $msg);
		$this->assertEquals($exp1, $exp);
		
		// add another
		$msg2 = 'test 2';
		$exp2 = new Exception('test 2');
		$mapper->addMessage($msg2, $exp2);
		
		$this->assertTrue($mapper->hasMessages());
		$this->assertTrue($mapper->hasExceptions());
		
		// count it
		$this->assertEquals(2, count($mapper->getMessages()));
		$this->assertEquals(2, count($mapper->getExceptions()));
		
		// add message only
		$msg3 = 'test 3';
		$mapper->addMessage($msg3);
		
		$this->assertTrue($mapper->hasMessages());
		$this->assertTrue($mapper->hasExceptions());
		
		// count it
		$this->assertEquals(3, count($mapper->getMessages()));
		$this->assertEquals(2, count($mapper->getExceptions()));
		
		// clear
		$mapper->clearMessages()->clearExceptions();
		
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
	}
	
	/**
	 * @depends testObject
	 * @param $mapper
	 */
	public function testReset($mapper)
	{
		$msg1 = 'test 1';
		$exp1 = new Exception('test');
		$mapper->addMessage($msg1, $exp1);
		
		// reset
		$mapper->reset();
		
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
	}
	
	/**
	 * @depends testObject
	 * @param $mapper
	 */
	public function testTable($mapper)
	{
		// table should be null since not yet set
		$this->assertEquals(null, $mapper->getTable());
		
		$table = 'test_table';
		$mapper->setTable($table);
		$this->assertEquals($table, $mapper->getTable());
	}
	
	/**
	 * @depends testObject
	 * @param $mapper
	 */
	public function testDb($mapper)
	{
		// db should be 'default' if not set
		$db = 'default';
		$this->assertEquals($db, $mapper->getDb());
		
		$newDb = 'slaveDb';
		$mapper->setDb($newDb);
		$this->assertEquals($newDb, $mapper->getDb());
		
		// reset back to default
		$mapper->reset();
		$this->assertEquals($db, $mapper->getDb());
	}
}