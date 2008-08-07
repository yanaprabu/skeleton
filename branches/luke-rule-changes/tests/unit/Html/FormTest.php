<?php
require_once('A/Html/Form.php');

class Html_FormTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_FormNotNull() {
  		$obj = new A_Html_Form();
		
		$this->assertEqual($obj->render(array('name'=>'foo')), '<xxx name="foo">foobar</xxx>');
	}
	
}
