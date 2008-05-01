<?php
require_once('A/Email/Multipart.php');

class Email_MultipartTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testEmail_MultipartNotNull() {
  		$Email_Multipart = new A_Email_Multipart();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
