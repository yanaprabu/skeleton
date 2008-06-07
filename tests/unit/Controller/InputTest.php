<?php
require_once('A/Controller/Input.php');

class Controller_InputTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testController_InputNotNull() {
  		$Controller_Input = new A_Controller_Input();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
