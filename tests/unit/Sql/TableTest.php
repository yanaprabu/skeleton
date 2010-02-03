<?php

class Sql_TableTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_Table() {
  		$Sql_Table = new A_Sql_Table('foo');
 		$this->assertEqual($Sql_Table->render(), "foo");

	  	$Sql_Table = new A_Sql_Table(array('foo'));
 		$this->assertEqual($Sql_Table->render(), "foo");

  		$Sql_Table = new A_Sql_Table(array('foo', 'bar'));
 		$this->assertEqual($Sql_Table->render(), "foo, bar");
	}
	
}
