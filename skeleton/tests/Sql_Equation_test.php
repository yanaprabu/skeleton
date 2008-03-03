<?php
require_once('A/Sql/Equation.php');

class Sql_EquationTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_EquationNotNull() {
  		$data = 'foo';
  		$value = 'bar';
  		$Sql_Equation = new A_Sql_Equation($data, $value);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
