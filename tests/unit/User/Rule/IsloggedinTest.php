<?php
include_once dirname(__FILE__) . '/../UserMock.php';

class User_Rule_IsLoggedInTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testUser_Rule_IsLoggedIn() {
		$level = 5;
		$errorMsg = 'error';
		$rule = new A_User_Rule_Isloggedin($errorMsg);
		
		$user = new UserMock();
		
		$this->assertFalse($rule->isValid($user));
		$this->assertFalse($rule->setUser($user)->isValid());
		$this->assertEqual($rule->getErrorMsg(), $errorMsg);
		
		$user->setLoggedIn(true);
		$this->assertTrue($rule->isValid($user));
		$this->assertTrue($rule->setUser($user)->isValid());
		$this->assertEqual($rule->getErrorMsg(), '');
	}
	
}
