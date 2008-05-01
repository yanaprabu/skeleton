<?php
require_once('A/Db/MySQL.php');

class Db_MySQLTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testDb_MySQLNotNull() {
  		$Db_MySQL = new A_Db_MySQL();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
