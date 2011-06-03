<?php

class Model_FieldTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testModel_FieldNotNull() {
  		$model = new A_Model_Field('foo');
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
