<?php

class Sql_SelectTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}

	function testSql_SelectColumns() {
		$Sql_Select = new A_Sql_Select();
		$this->assertEqual($Sql_Select
							->columns('foo', 'bar')
							->from('foobar')
							->render(), "SELECT foo, bar FROM foobar");

		$Sql_Select = new A_Sql_Select();
		$this->assertEqual($Sql_Select
							->columns(array('foo', 'bar'))
							->from('foobar')
							->render(), "SELECT foo, bar FROM foobar");
	}

	function testSql_SelectWhere() {
		$Sql_Select = new A_Sql_Select();
		$this->assertEqual($Sql_Select
							->columns('foo', 'bar')
							->from('foobar')
							->where('baz', 'faz')
							->render(), "SELECT foo, bar FROM foobar WHERE (baz='faz')");

		$Sql_Select = new A_Sql_Select();
		$this->assertEqual($Sql_Select
							->columns('foo', 'bar')
							->from('foobar')
							->where(array('baz LIKE '=>'faz', 'start=NOW()'))
							->render(), "SELECT foo, bar FROM foobar WHERE (baz LIKE 'faz' AND start=NOW())");
	}
	
	function testSql_SelectJoins() {

		$Sql_Select = new A_Sql_Select();
		$this->assertEqual($Sql_Select
							->columns('foo', 'bar')
							->from('foobar')
							->join('foobar', 'id', 'barfoo', 'foobar_id')
							->render(), "SELECT foo, bar FROM foobar JOIN barfoo ON foobar.id=barfoo.foobar_id");

	}
	
}
