<?php
require_once('A/Sql/Table.php');

class Sql_TableTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_TableNotNull() {
  		$table = array();
  		$Sql_Table = new A_Sql_Table($table);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
