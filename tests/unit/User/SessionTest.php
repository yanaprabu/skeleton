<?php

class User_SessionTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testUser_SessionNotNull() {
  		$session = new A_Session();
  		$namespace = null;
  		$User_Session = new A_User_Session($session, $namespace);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
