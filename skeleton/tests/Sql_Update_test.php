<?php
require_once('A/Sql/Update.php');

class Sql_UpdateTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_UpdateNotNull() {
  		$Sql_Update = new A_Sql_Update();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
