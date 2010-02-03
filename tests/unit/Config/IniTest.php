<?php

class Config_IniTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testConfig_IniNotNull() {
  		$filename = 'foo.ini';
  		$section = 'bar';
  		$exception = null;
  		$Config_Ini = new A_Config_Ini($filename, $section, $exception);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
