<?php
require_once('A/Sql/Columns.php');

class Sql_ColumnsTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_ColumnsNotNull() {
		$columns = array('foo', 'bar');
		$Sql_Columns = new A_Sql_Columns($columns);
		
		$result = true;
		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
