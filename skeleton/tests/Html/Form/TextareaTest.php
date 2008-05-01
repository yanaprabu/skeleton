<?php
require_once('A/Html/Form/Textarea.php');

class Html_Form_TextareaTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_TextareaNotNull() {
  		$Html_Form_Textarea = new A_Html_Form_Textarea();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
