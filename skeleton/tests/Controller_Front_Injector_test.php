<?php
require_once('A/Controller/Front/Injector.php');

class Controller_Front_InjectorTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testController_Front_InjectorNotNull() {
		$property='foo';
		$value=null;
  		$Controller_Front_Injector = new A_Controller_Front_Injector($property, $value);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
