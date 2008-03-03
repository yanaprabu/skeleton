<?php
require_once('A/Sql/List.php');

class Sql_ListTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_ListNotNull() {
  		$element = null;
  		$Sql_List = new A_Sql_List($element);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
