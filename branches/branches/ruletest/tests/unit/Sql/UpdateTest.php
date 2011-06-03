<?php

class Sql_UpdateTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_UpdateSet() {
		$Sql_Update = new A_Sql_Update();
		$this->assertEqual($Sql_Update->table('foobar')->set('foo', 'bar')->render(), "UPDATE foobar SET foo = 'bar'");

		$Sql_Update = new A_Sql_Update();
		$this->assertEqual($Sql_Update->table('foobar')->set('foo', "bar's")->render(), "UPDATE foobar SET foo = 'bar\\'s'");

		// sets do not overwrite previous sets
		$Sql_Update = new A_Sql_Update();
		$this->assertEqual($Sql_Update
							->table('foobar')
							->set('foo', 'bar')
							->set('baz', 'faz')
							->render(), "UPDATE foobar SET foo = 'bar', baz = 'faz'");

			// sets do not overwrite previous sets
		$Sql_Update = new A_Sql_Update();
		$this->assertEqual($Sql_Update
							->table('foobar')
							->set(array('foo'=>'bar', 'baz'=>'faz'))
							->render(), "UPDATE foobar SET foo = 'bar', baz = 'faz'");
	}
	
	function testSql_UpdateWhere() {
		$Sql_Update = new A_Sql_Update();
		$this->assertEqual($Sql_Update
							->table('foobar')
							->set('foo', 'bar')
							->where('baz', 'faz')
							->render(), "UPDATE foobar SET foo = 'bar' WHERE (baz = 'faz')");
	}
	
}
