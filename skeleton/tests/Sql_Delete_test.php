<?php
require_once('A/Sql/Delete.php');

class Sql_DeleteTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_DeleteNotNull() {
  		$Sql_Delete = new A_Sql_Delete();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
