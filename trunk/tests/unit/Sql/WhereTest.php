<?php
require_once('A/Sql/Where.php');

class Sql_WhereTest extends UnitTestCase {
	
	function whereUp() {
	}
	
	function TearDown() {
	}
	
    function testEmptyStringReturnsEmpty() {
        $where = new A_Sql_Where();
        $this->assertEqual($where->render(), '');
    }
 
}
