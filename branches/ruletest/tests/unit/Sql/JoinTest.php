<?php

class Sql_JoinTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_JoinConstructorNoParams() {
		$Sql_Join = new A_Sql_Join();
		$this->assertEqual($Sql_Join->render(), "");		// requires two tables
	}
	
	function testSql_JoinConstructorOneTable() {
		$Sql_Join = new A_Sql_Join('bar');
		$this->assertEqual($Sql_Join->render(), " INNER JOIN bar");		// requires two tables
	}
	
	function testSql_JoinConstructorTwoTables() {
		$Sql_Join = new A_Sql_Join('bar', 'foo');
		$this->assertEqual($Sql_Join->render(), " INNER JOIN bar");
	}
	
	function testSql_JoinConstructorTwoTablesAndType() {
		$Sql_Join = new A_Sql_Join('bar', 'foo', 'LEFT');
		$this->assertEqual($Sql_Join->render(), " LEFT JOIN bar");
	}
	
	function testSql_JoinConstructorTwoTablesAndTypeAndOn() {
		$Sql_Join = new A_Sql_Join('bar', 'foo', 'LEFT');
		$Sql_Join->on('foo_id', 'id');
		$this->assertEqual($Sql_Join->render(), " LEFT JOIN bar ON (bar.foo_id = foo.id)");
	}
	
	function testSql_JoinConstructorNoParamsJoinTwoTablesAndTypeAndOn() {
		$Sql_Join = new A_Sql_Join();
		$Sql_Join->join('bar', 'foo')->on('foo_id', 'id');
		$this->assertEqual($Sql_Join->render(), " INNER JOIN bar ON (bar.foo_id = foo.id)");
	}
	
}
