<?php

class A_Config_BaseTest extends A_Config_Base {
	
}

class Config_BaseTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testConfig_BaseNotNull() {
  		$filename='';
  		$section='';
  		$exception=null;
  		$Config_Base = new A_Config_BaseTest($filename, $section, $exception);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
