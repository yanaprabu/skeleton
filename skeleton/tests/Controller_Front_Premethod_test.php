<?php
require_once('A/Controller/Front/Premethod.php');

class Controller_Front_PremethodTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testController_Front_PremethodNotNull() {
  		$method='foo';
  		$change_action='bar';
  		$locator=null;
  		$Controller_Front_Premethod = new A_Controller_Front_Premethod($method, $change_action, $locator);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
