<?php
require_once('A/Html/Form/Checkbox.php');

class Html_Form_CheckboxTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_CheckboxNotNull() {
  		$Html_Form_Checkbox = new A_Html_Form_Checkbox();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
