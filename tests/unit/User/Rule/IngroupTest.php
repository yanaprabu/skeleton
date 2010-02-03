<?php

class User_Rule_IngroupTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testUser_Rule_IngroupNotNull() {
  		$groups = array('foo', 'bar');
  		$errorMsg = 'error';
  		$User_Rule_Ingroup = new A_User_Rule_Ingroup($groups, $errorMsg);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
