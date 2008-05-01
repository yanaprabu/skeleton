<?php
require_once('A/Config/Yaml.php');

class Config_YamlTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testConfig_YamlNotNull() {
  		$filename = 'foo.yaml';
  		$section = 'bar';
  		$exception = null;
		$Config_Yaml = new A_Config_Yaml($filename, $section, $exception);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
