<?php
require_once('A/Sql/Set.php');

class Sql_SetTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
    function testEmptyStringReturnsEmpty() {
        $set = new A_Sql_Set();
        $this->assertEqual($set->render(), '');
    }
 
}
