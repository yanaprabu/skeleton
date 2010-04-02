<?php

class Sql_FromTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_FromConstructor() {
  		$Sql_From = new A_Sql_From('foo');
 		$this->assertEqual($Sql_From->render(), "foo");

	  	$Sql_From = new A_Sql_From(array('foo'));
 		$this->assertEqual($Sql_From->render(), "foo");

  		$Sql_From = new A_Sql_From(array('foo', 'bar'));
 		$this->assertEqual($Sql_From->render(), "foo INNER JOIN bar");

  		$Sql_From = new A_Sql_From(array('foo', new A_Sql_Join('bar')));
 		$this->assertEqual($Sql_From->render(), "foo INNER JOIN bar");
	}
	
	function testSql_FromTable() {
  		$Sql_From = new A_Sql_From();
		$Sql_From->table('foo');
 		$this->assertEqual($Sql_From->render(), "foo");

	  	$Sql_From = new A_Sql_From();
		$Sql_From->table(array('foo'));
	  	$this->assertEqual($Sql_From->render(), "foo");

  		$Sql_From = new A_Sql_From();
		$Sql_From->table(array('foo', 'bar'));
  		$this->assertEqual($Sql_From->render(), "foo INNER JOIN bar");

		$Sql_From = new A_Sql_From();
		$Sql_From->table(array('foo', new A_Sql_Join('bar')));
		$this->assertEqual($Sql_From->render(), "foo INNER JOIN bar");
	}
	
	function testSql_FromJoin() {
  		$Sql_From = new A_Sql_From();
		$Sql_From->table('foo')->join('bar');
 		$this->assertEqual($Sql_From->render(), "foo INNER JOIN bar");

	  	$Sql_From = new A_Sql_From();
		$Sql_From->table('foo')->join('bar', 'LEFT');
	  	$this->assertEqual($Sql_From->render(), "foo LEFT JOIN bar");

  		$Sql_From = new A_Sql_From();
		$Sql_From->table('foo')->join('bar', 'foo', 'RIGHT');
  		$this->assertEqual($Sql_From->render(), "foo RIGHT JOIN bar");
	}
	
	function testSql_FromJoinOn() {
		$Sql_From = new A_Sql_From();
		$Sql_From->table('foo')->join('bar', 'baz')->on('foo_id', 'id');
		$this->assertEqual($Sql_From->render(), "foo INNER JOIN bar ON (bar.foo_id = baz.id)");
	}
	
}
