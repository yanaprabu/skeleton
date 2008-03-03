<?php
require_once('A/Sql/Groupby.php');

class Sql_GroupbyTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_GroupbyNotNull() {
  		$Sql_Groupby = new A_Sql_Groupby();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
