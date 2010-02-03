<?php

class Db_ActiverecordTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testDb_ActiverecordNotNull() {
  		$Db_Activerecord = new A_Db_Activerecord();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
