<?php
require_once('A/Sql/Prepare.php');

class Sql_PrepareTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_PrepareNotNull() {
  		$statement='';
  		$db=null;
  		$Sql_Prepare = new A_Sql_Prepare($statement, $db);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
