<?php
require_once('A/Sql/Values.php');

class Sql_ValuesTest extends UnitTestCase {
	
	function valuesUp() {
	}
	
	function TearDown() {
	}
	
    function testEmptyStringReturnsEmpty() {
        $values = new A_Sql_Values();
        $this->assertEqual($values->render(), '');
    }
 
}
