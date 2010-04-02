<?php

class Sql_ValuesTest extends UnitTestCase {
	
	function valuesUp() {
	}
	
	function TearDown() {
	}
	
	function testEmptyStringReturnsEmpty() {
		$values = new A_Sql_Values('foo', 42);
		$this->assertEqual($values->render(), '(foo) VALUES (42)');

		$values = new A_Sql_Values(array('foo'=>42));
		$this->assertEqual($values->render(), '(foo) VALUES (42)');

		$values = new A_Sql_Values(array('foo'=>42, 'bar'=>'baz'));
		$this->assertEqual($values->render(), "(foo, bar) VALUES (42, 'baz')");
	}
 
}
