<?php

class Sql_InsertTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_InsertNull() {
 		
		$Sql_Insert = new A_Sql_Insert();
  		$this->assertEqual($Sql_Insert->render(), '');

		$Sql_Insert = new A_Sql_Insert();
  		$this->assertEqual($Sql_Insert->table('foobar')->render(), '');

	}
	
	function testSql_InsertValues() {
 		
		$Sql_Insert = new A_Sql_Insert();
  		$this->assertEqual($Sql_Insert->table('foobar')->values('foo', 'bar')->render(), "INSERT INTO foobar (foo) VALUES ('bar')");

  		$Sql_Insert = new A_Sql_Insert();
  		$this->assertEqual($Sql_Insert->table('foobar')->values(array('foo'=>'bar'))->render(), "INSERT INTO foobar (foo) VALUES ('bar')");

  		$Sql_Insert = new A_Sql_Insert();
  		$this->assertEqual($Sql_Insert->table('foobar')->values(array('foo'=>'bar', 'faz'=>'baz'))->render(), "INSERT INTO foobar (foo, faz) VALUES ('bar', 'baz')");

	}
	
	function testSql_InsertSelect() {
 		
		$Sql_Insert = new A_Sql_Insert();
		$Sql_Insert->table('foobar')
					->columns(array('foo', 'bar'))
					->select()->columns('fox','box')->from('barfoo')->where('id!=', 42);
  		$this->assertEqual($Sql_Insert->render(), "INSERT INTO foobar (foo, bar) SELECT fox, box FROM barfoo WHERE (id!= 42)");

	}
	
}
