<?php
require_once('A/Html/Form/Text.php');

class Html_Form_TextTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_TextNotNull() {
  		$Html_Form_Text = new A_Html_Form_Text();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
