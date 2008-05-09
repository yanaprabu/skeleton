<?php
require_once('A/Sql/List.php');

class Sql_ListTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_List() {
  		$element = array('foo', 'bar', 'baz');
  		$Sql_List = new A_Sql_List($element);
		$this->assertEqual($Sql_List->render(), "foo, bar, baz");
	}
	
}
