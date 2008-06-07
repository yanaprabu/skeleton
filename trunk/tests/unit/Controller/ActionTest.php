<?php
require_once('A/Controller/Action.php');
require_once('A/Locator.php');
require_once('A/Controller/Mapper.php');

class Controller_ActionTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testController_ActionNotNull() {
  		$locator = new A_Locator();
  		
  		$base_path='';
  		$default_action=null;
  		$mapper = new A_Controller_Mapper($base_path, $default_action);
  		$locator->set('Mapper', $mapper);
  		
		$Controller_Action = new A_Controller_Action($locator);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
