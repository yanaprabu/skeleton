<?php

class Sql_SetTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
    function testEmptyStringReturnsEmpty() {
        $set = new A_Sql_Set();
        $this->assertEqual($set->render(), '');
    }
 
    function testOneExpression() {
        $set = new A_Sql_Set();
        $set->addExpression('foo', 42);
        $this->assertEqual($set->render(), ' SET foo = 42');
    }
 
    function testMultiExpression() {
        $set = new A_Sql_Set();
        $set->addExpression('foo >', 42);
        $set->addExpression('bar', 'baz');
        $this->assertEqual($set->render(), " SET foo > 42, bar = 'baz'");
    }
 
}
