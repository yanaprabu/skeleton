<?php

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
