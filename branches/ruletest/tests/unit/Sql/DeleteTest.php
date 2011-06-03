<?php

class Sql_DeleteTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_DeleteConstructorArgs() {
		
  		$Sql_Delete = new A_Sql_Delete();
  		$this->assertEqual($Sql_Delete->render(), '');

  		$Sql_Delete = new A_Sql_Delete('foo');
  		$this->assertEqual($Sql_Delete->render(), 'DELETE FROM foo');

  		$Sql_Delete = new A_Sql_Delete('foo', array('bar'=>1));
  		$this->assertEqual($Sql_Delete->render(), "DELETE FROM foo WHERE (bar = 1)");

  		$Sql_Delete = new A_Sql_Delete('foo', array('bar'=>1, 'faz'=>'baz'));
  		$this->assertEqual($Sql_Delete->render(), "DELETE FROM foo WHERE (bar = 1 AND faz = 'baz')");
	}
	
	function testSql_DeleteTableWhere() {
		
  		$Sql_Delete = new A_Sql_Delete();
  		$this->assertEqual($Sql_Delete->table('foo')->render(), 'DELETE FROM foo');

  		$Sql_Delete = new A_Sql_Delete();
  		$this->assertEqual($Sql_Delete->table('foo')->where(array('bar'=>1))->render(), "DELETE FROM foo WHERE (bar = 1)");

  		$Sql_Delete = new A_Sql_Delete();
  		$this->assertEqual($Sql_Delete->table('foo')->where(array('bar'=>1, 'faz'=>'baz'))->render(), "DELETE FROM foo WHERE (bar = 1 AND faz = 'baz')");
	}
	
}
