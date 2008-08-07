<?php
require_once('A/Html/Form/Radiocheckbox.php');

class Html_Form_RadiocheckboxTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_RadiocheckboxNotNull() {
  		$Html_Form_Radiocheckbox = new A_Html_Form_Radiocheckbox();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
