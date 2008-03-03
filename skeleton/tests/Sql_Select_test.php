<?php
require_once('A/Sql/Select.php');

class Sql_SelectTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_SelectNotNull() {
  		$Sql_Select = new A_Sql_Select();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
