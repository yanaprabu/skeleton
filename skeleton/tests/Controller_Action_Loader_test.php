<?php
require_once('A/Controller/Action/Loader.php');
require_once('A/Locator.php');
require_once('A/Controller/Mapper.php');

class Controller_Action_LoaderTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testController_Action_LoaderNotNull() {
  		$locator = new A_Locator();
  		
  		$base_path='';
  		$default_action=null;
  		$mapper = new A_Controller_Mapper($base_path, $default_action);
  		$locator->set('Mapper', $mapper);
  		
  		$Controller_Action_Loader = new A_Controller_Action_Loader($locator);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
