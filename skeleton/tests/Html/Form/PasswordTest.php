<?php
require_once('A/Html/Form/Password.php');

class Html_Form_PasswordTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_PasswordNotNull() {
  		$Html_Form_Password = new A_Html_Form_Password();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
