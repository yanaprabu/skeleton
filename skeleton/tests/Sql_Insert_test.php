<?php
require_once('A/Sql/Insert.php');

class Sql_InsertTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_InsertNotNull() {
  		$Sql_Insert = new A_Sql_Insert();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
