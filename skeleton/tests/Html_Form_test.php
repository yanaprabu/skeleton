<?php
require_once('A/Html/Form.php');

class Html_FormTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_FormNotNull() {
  		$Html_Form = new A_Html_Form();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
