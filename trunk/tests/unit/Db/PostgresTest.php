<?php

class Db_PostgresTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testDb_PostgresNotNull() {
  		$Db_Postgres = new A_Db_Postgres();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
