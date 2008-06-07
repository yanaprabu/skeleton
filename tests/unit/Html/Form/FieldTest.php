<?php
require_once('A/Html/Form/Field.php');

class Html_Form_FieldTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_FieldNotNull() {
  		$Html_Form_Field = new A_Html_Form_Field();
		
		$this->assertEqual($obj->render(array('name'=>'foo')), '<xxx name="foo">foobar</xxx>');
	}
	
}
