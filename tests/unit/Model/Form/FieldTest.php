<?php

class Model_Form_FieldTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testModel_Form_FieldNotNull() {
  		$form = new A_Model_Form();
		$field = new A_Model_Form_Field('foo');
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
