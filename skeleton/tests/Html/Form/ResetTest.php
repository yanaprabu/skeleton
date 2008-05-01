<?php
require_once('A/Html/Form/Reset.php');

class Html_Form_ResetTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_ResetNotNull() {
  		$Html_Form_Reset = new A_Html_Form_Reset();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
