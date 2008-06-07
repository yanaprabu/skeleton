<?php
require_once('A/Sql/Having.php');

class Sql_HavingTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
    function testEmptyStringReturnsEmpty() {
        $having = new A_Sql_Having();
        $this->assertEqual($having->render(), '');
    }
 
}
