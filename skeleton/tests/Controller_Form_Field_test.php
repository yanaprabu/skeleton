<?php
require_once('A/Controller/Form/Field.php');

class Controller_Form_FieldTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testController_Form_FieldNotNull() {
  		$Controller_Form_Field = new A_Controller_Form_Field();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
