<?php

class Sql_LogicalListTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_LogicalListEmpty() {
  		$element = array('foo', 'bar', 'baz');
  		$list = new A_Sql_LogicalList();
		$this->assertEqual($list->render(), '');
	}
	
	function testSql_LogicalListArrayOneElement() {
  		$list = new A_Sql_LogicalList($element);
 		$this->assertEqual($list->addExpression(array('foo'=>'bar'))->render(), "(foo = 'bar')");
	}
	
	function testSql_LogicalListArrayTwoElements() {
  		$list = new A_Sql_LogicalList($element);
 		$this->assertEqual($list->addExpression(array('foo'=>'bar', 'faz'=>'baz'))->render(), "(foo = 'bar' AND faz = 'baz')");
	}
	
}
