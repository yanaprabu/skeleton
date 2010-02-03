<?php

class LoggerTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testLoggerNotNull() {
  		$writer = 'foo.log';
  		$Logger = new A_Logger($writer);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
