<?php
require_once('A/Sql/Join.php');

class Sql_JoinTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_JoinNotNull() {
		$table1 = '';
		$field1 = '';
		$table2 = '';
		$field2 = '';
		$joinType = '';
  		$Sql_Join = new A_Sql_Join($table1, $field1, $table2, $field2, $joinType);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
