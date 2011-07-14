<?php

class Sql_LogicallistTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_LogicallistEmpty() {
  		$element = array('foo', 'bar', 'baz');
  		$list = new A_Sql_Logicallist();
		$this->assertEqual($list->render(), '');
	}
	
	function testSql_LogicallistArrayOneElement() {
  		$list = new A_Sql_Logicallist($element);
 		$this->assertEqual($list->addExpression(array('foo'=>'bar'))->render(), "(foo = 'bar')");
	}
	
	function testSql_LogicallistArrayTwoElements() {
  		$list = new A_Sql_Logicallist($element);
 		$this->assertEqual($list->addExpression(array('foo'=>'bar', 'faz'=>'baz'))->render(), "(foo = 'bar' AND faz = 'baz')");
	}
	
}
