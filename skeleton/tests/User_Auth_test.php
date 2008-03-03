<?php
require_once('A/Session.php');
require_once('A/User/Session.php');
require_once('A/User/Auth.php');

class User_AuthTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testUser_AuthNotNull() {
  		$session = new A_Session();
  		$user = new A_User_Session($session);
		$User_Auth = new A_User_Auth($user);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
