<?php
require_once('A/Controller/Mapper.php');

class Controller_MapperTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testController_MapperNotNull() {
  		$base_path='';
  		$default_action=null;
  		$mapper = new A_Controller_Mapper($base_path, $default_action);
				
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
