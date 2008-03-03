<?php
require_once('A/Sql/Expression.php');

class Sql_ExpressionTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_ExpressionNotNull() {
  		$data = 'foo';
  		$value = 'bar';
		$Sql_Expression = new A_Sql_Expression($data, $value);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
