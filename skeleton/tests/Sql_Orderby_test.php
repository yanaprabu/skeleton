<?php
require_once('A/Sql/Orderby.php');

class Sql_OrderbyTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_OrderbyNotNull() {
  		$Sql_Orderby = new A_Sql_Orderby();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
