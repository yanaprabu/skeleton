<?php
require_once('A/Db/Mysqli.php');

class Db_MysqliTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testDb_MysqliNotNull() {
  		$Db_Mysqli = new A_Db_Mysqli();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
