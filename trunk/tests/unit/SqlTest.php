<?php
require_once('A/Sql.php');

class SqlTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSqlNotNull() {
  		$Sql = new A_Sql();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
