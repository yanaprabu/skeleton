<?php

class A_Config_AbstractTest extends A_Config_Abstract {
	
}

class Config_AbstractTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testConfig_AbstractNotNull() {
  		$filename='';
  		$section='';
  		$exception=null;
  		$Config_Abstract = new A_Config_AbstractTest($filename, $section, $exception);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
