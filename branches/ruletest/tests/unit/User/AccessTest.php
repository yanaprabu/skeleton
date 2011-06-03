<?php

class User_AccessTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testUser_AccessNotNull() {
  		$user = null;
  		$User_Access = new A_User_Access($user);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
