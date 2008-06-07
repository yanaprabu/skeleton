<?php
require_once('A/Sql/Prepare.php');

class Sql_PrepareTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}

	function testSql_PrepareNumberdArgs() {
  		$Sql_Prepare = new A_Sql_Prepare('? ? ?', 'foo', 'bar', 'baz');
  		$this->assertEqual($Sql_Prepare->bind()->render(), "foo bar baz");

  		$Sql_Prepare = new A_Sql_Prepare('? ? ?');
  		$this->assertEqual($Sql_Prepare->bind('foo', 'bar', 'baz')->render(), "foo bar baz");

 		$Sql_Prepare = new A_Sql_Prepare();
 		$this->assertEqual($Sql_Prepare->statement('? ? ?')->bind('foo', 'bar', 'baz')->render(), "foo bar baz");
	}
	
	function testSql_PrepareNamedArgs() {
		$Sql_Prepare = new A_Sql_Prepare(':foo :bar :baz', array(':foo'=>1, ':bar'=>2, ':baz'=>3));
		$this->assertEqual($Sql_Prepare->render(), "1 2 3");

		$Sql_Prepare = new A_Sql_Prepare(':foo :bar :baz');
		$this->assertEqual($Sql_Prepare->bind(array(':foo'=>1, ':bar'=>2, ':baz'=>3))->render(), "1 2 3");

		$Sql_Prepare = new A_Sql_Prepare();
		$this->assertEqual($Sql_Prepare->statement(':foo :bar :baz')->bind(array(':foo'=>1, ':bar'=>2, ':baz'=>3))->render(), "1 2 3");

	}
	
	function testSql_PrepareMixedArgs() {
		$Sql_Prepare = new A_Sql_Prepare();
		$this->assertEqual($Sql_Prepare->statement('? :foo ? :bar ? :baz')->bind('foo', array(':baz'=>3), 'bar', array(':foo'=>1, ':bar'=>2), 'baz')->render(), "foo 1 bar 2 baz 3");

	}
	
}
