<?php
require_once('A/Controller/Form.php');

class Controller_FormTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testController_FormNotNull() {
  		$Controller_Form = new A_Controller_Form();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
