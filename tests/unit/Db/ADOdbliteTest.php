<?php

class Db_ADOdbliteTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testDb_ADOdbliteNotNull() {
  		$Db_ADOdblite = new A_Db_ADOdblite();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
