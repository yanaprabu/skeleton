<?php

class Logger_FileTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testLogger_FileNotNull() {
  		$filename = 'foo.log';
  		$Logger_File = new A_Logger_File($filename);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
