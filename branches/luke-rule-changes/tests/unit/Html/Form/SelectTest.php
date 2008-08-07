<?php
require_once('A/Html/Form/Select.php');

class Html_Form_SelectTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_SelectNotNull() {
  		$Html_Form_Select = new A_Html_Form_Select();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
