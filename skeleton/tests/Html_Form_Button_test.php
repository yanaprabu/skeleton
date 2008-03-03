<?php
require_once('A/Html/Form/Button.php');

class Html_Form_ButtonTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_ButtonNotNull() {
  		$Html_Form_Button = new A_Html_Form_Button();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
