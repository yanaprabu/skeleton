<?php
require_once('A/Html/Form/Submit.php');

class Html_Form_SubmitTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_SubmitNotNull() {
  		$Html_Form_Submit = new A_Html_Form_Submit();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
