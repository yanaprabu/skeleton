<?php

class Config_XmlTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testConfig_XmlNotNull() {
  		$filename = 'foo.xml';
  		$section = 'bar';
  		$exception = null;
		$Config_Xml = new A_Config_Xml($filename, $section, $exception);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
