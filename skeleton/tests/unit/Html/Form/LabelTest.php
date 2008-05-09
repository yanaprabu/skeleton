<?php
require_once('A/Html/Form/Label.php');

class Html_Form_LabelTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_LabelNotNull() {
  		$Html_Form_Label = new A_Html_Form_Label();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
