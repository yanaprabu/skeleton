<?php
require_once('A/Controller/Frontsimple.php');

class Controller_FrontsimpleTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testController_FrontsimpleNotNull() {
  		$Controller_Frontsimple = new A_Controller_Frontsimple();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
