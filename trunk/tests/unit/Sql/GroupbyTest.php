<?php
require_once('A/Sql/Groupby.php');

class Sql_GroupbyTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
    function testEmptyStringColumnReturnsEmpty() {
        $groupby = new A_Sql_Groupby();
        $this->assertEqual($groupby->render(), '');
    }
 
    function testSingleStringParameterColumn() {
        $groupby = new A_Sql_Groupby('bar');
        $this->assertEqual($groupby->render(), 'GROUP BY bar');
    }   
 
    function testMultipleStringParameterColumns() {
        $groupby = new A_Sql_Groupby('foo', 'bar', 'fee');
        $this->assertEqual($groupby->render(), 'GROUP BY foo, bar, fee');
    }       
    
    function testSingleArrayOfParameterColumns() {
        $groupby = new A_Sql_Groupby(array('foo', 'bar'));
        $this->assertEqual($groupby->render(), 'GROUP BY foo, bar');
    }   
}
