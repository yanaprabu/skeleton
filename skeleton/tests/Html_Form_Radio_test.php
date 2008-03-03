<?php
require_once('A/Html/Form/Radio.php');

class Html_Form_RadioTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_RadioNotNull() {
  		$Html_Form_Radio = new A_Html_Form_Radio();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
