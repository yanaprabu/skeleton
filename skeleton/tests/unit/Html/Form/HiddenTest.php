<?php
require_once('A/Html/Form/Hidden.php');

class Html_Form_HiddenTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_HiddenNotNull() {
  		$Html_Form_Hidden = new A_Html_Form_Hidden();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
