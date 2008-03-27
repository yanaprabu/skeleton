<?php
require_once('A/Sql/Columns.php');

class Sql_ColumnsTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_ColumnsNotNull() {
  		$Sql_Columns = new A_Sql_Columns('foo');
  		$this->assertEqual($Sql_Columns->render(), 'foo');

  		$Sql_Columns = new A_Sql_Columns('foo', 'bar');
  		$this->assertEqual($Sql_Columns->render(), "foo, bar");

  		$Sql_Columns = new A_Sql_Columns(array('foo', 'bar'));
  		$this->assertEqual($Sql_Columns->render(), "foo, bar");
	}
	
}
