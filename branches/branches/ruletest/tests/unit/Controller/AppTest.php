<?php

class Controller_AppTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testController_AppNotNull() {
  		$Controller_App = new A_Controller_App();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
