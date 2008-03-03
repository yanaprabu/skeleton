<?php
require_once('A/Html/Form/Fieldset.php');

class Html_Form_FieldsetTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_FieldsetNotNull() {
  		$Html_Form_Fieldset = new A_Html_Form_Fieldset();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
