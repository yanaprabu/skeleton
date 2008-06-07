<?php
require_once('A/Rule/Captcha.php');

class Rule_CaptchaTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRule_CaptchaNotNull() {
  		$field = 'foo';
  		$errorMsg = 'foo error';
  		$renderer = '';
  		$session = null;
  		$sessionkey = null;
		$Rule_Captcha = new A_Rule_Captcha($field, $errorMsg, $renderer, $session, $sessionkey);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
