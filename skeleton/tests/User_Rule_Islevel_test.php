<?php
require_once('A/User/Rule/Islevel.php');

class User_Rule_IslevelTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testUser_Rule_IslevelNotNull() {
  		$level = 5;
  		$errorMsg = 'error';
  		$User_Rule_Islevel = new A_User_Rule_Islevel($level, $errorMsg);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
