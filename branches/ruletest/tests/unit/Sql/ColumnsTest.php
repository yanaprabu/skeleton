<?php

class Sql_ColumnsTest extends UnitTestCase {
	function setUp() {
	}
	
	function TearDown() {
	}
	
    function testEmptyStringColumnReturnsEmpty() {
        $columns = new A_Sql_Columns();
        $this->assertEqual($columns->render(), '');
    }
 
    function testSingleStringParameterColumn() {
        $columns = new A_Sql_Columns('bar');
        $this->assertEqual($columns->render(), 'bar');
    }   
 
    function testMultipleStringParameterColumns() {
        $columns = new A_Sql_Columns('foo', 'bar', 'fee');
        $this->assertEqual($columns->render(), 'foo, bar, fee');
    }       
    
    function testSingleArrayOfParameterColumns() {
        $columns = new A_Sql_Columns(array('foo', 'bar'));
        $this->assertEqual($columns->render(), 'foo, bar');
    }   
}