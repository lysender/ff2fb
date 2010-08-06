<?php defined('SYSPATH') or die('No direct script access.');

class Dc_UuidTest extends PHPUnit_Framework_TestCase
{
    public function testObject()
    {
        $obj = Dc_Uuid::mint(4);
        $this->assertType('Dc_Uuid', $obj);
    }
    
    public function testBinary()
    {
        $id = Dc_Uuid::mint(4);
        $bin = $id->bytes;
        $this->assertType('string', $bin);
        $this->assertEquals(16, strlen($bin));
    }
    
    public function testString()
    {
        $id = Dc_Uuid::mint(4);
        $str = $id->string;
        $this->assertType('string', $str);
        $this->assertEquals(36, strlen($str));
    }
    
    public function testConvert()
    {
        $id = Dc_Uuid::mint(4);
        $binId = $id->bytes;
        $this->assertEquals(16, strlen($binId));
        $strId = $id->string;
        $this->assertEquals(36, strlen($strId));
        
        $convId = Dc_Uuid::import($binId);
        $convBin = $convId->bytes;
        $convStr = $convId->string;
        $this->assertType('string', $convStr);
        $this->assertEquals($strId, $convStr);
        $this->assertEquals($convBin, $binId);
        
        $this->assertEquals(36, strlen($convStr));
        $noDash = str_replace('-', '', $convStr);
        $this->assertEquals(32, strlen($noDash));
    }
    
    public function testDashes()
    {
        $id = Dc_Uuid::mint(4);
        $origStr = $id->string;
        $origBin = $id->bytes;
        
        // remove dashes
        $noDashes = str_replace('-', '', $origStr);
        $dashedObject = Dc_Uuid::import($noDashes);
        $putDashes = $dashedObject->string;
        $this->assertEquals($putDashes, $origStr);
        
        // try to convert to bin
        $bin2 = $dashedObject->bytes;
        $this->assertEquals($bin2, $origBin);
        
        // try to convert back to string
        $obj3 = Dc_Uuid::import($bin2);
        $str2 = $obj3->string;
        $this->assertEquals($origStr, $str2);
    }
}