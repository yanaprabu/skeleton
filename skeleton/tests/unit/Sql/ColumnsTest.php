<?php
require_once('A/Sql/Columns.php');

class Sql_ColumnsTest extends UnitTestCase {
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_ColumnsDefaultColumn() {
		$columns = new A_Sql_Columns();
		$this->assertEqual($columns->render(), '');
	}

	function testSql_ColumnsOnlyWildcardColumn() {
		$columns = new A_Sql_Columns('*');
		$this->assertEqual($columns->render(), '*');
	}
	
	function testSql_ColumnsWildcardWithMoreColumn() {
		$columns = new A_Sql_Columns('*', 'foo', 'bar');
# What should the output be?
#		$this->assertEqual($columns->render(), '*', 
#			'All other columns should be ignored if wildcard is present in column list ' . $columns->render());
	}	
	
	function testSql_ColumnsArrayColumns() {
		$columns = new A_Sql_Columns(array('foo', 'bar'));
		$this->assertEqual($columns->render(), 'foo, bar');
	}

	function testSql_ColumnsSingleParameterColumns() {
		$columns = new A_Sql_Columns('foobar');
		$this->assertEqual($columns->render(), 'foobar');
	}	

	function testSql_ColumnsMultipleParameterColumns() {
		$columns = new A_Sql_Columns('foo', 'bar', 'fee', 'bah');
		$this->assertEqual($columns->render(), 'foo, bar, fee, bah');
	}	
}
