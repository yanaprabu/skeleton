<?php

class Sql_WhereTest extends UnitTestCase {
	
	function whereUp() {
	}
	
	function TearDown() {
	}
	
    function testEmptyStringReturnsEmpty() {
        $where = new A_Sql_Where();
        $this->assertEqual($where->render(), '');
    }
 
	function testSql_LogicalListArrayOneElement() {
        $where = new A_Sql_Where();	
 		$this->assertEqual($where->addExpression(array('foo'=>'bar'))->render(), " WHERE (foo = 'bar')");
	}
	
	function testSql_LogicalListArrayTwoElements() {
        $where = new A_Sql_Where();	
 		$this->assertEqual($where->addExpression(array('foo'=>'bar', 'faz'=>'baz'))->render(), " WHERE (foo = 'bar' AND faz = 'baz')");
	}
	
}
