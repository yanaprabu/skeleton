<?php

class Controller_FrontTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testController_FrontNotNull() {
  		$base_path='';
  		$default_action=null;
  		$mapper = new A_Controller_Mapper($base_path, $default_action);

  		$error_action = null;
		$prefilters = array();
  		$Controller_Front = new A_Controller_Front($mapper, $error_action, $prefilters);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
