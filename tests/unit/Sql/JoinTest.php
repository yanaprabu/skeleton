<?php
require_once('A/Sql/Join.php');

class Sql_JoinTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testSql_Join() {
		$Sql_Join = new A_Sql_Join('foo', 'id', 'bar', 'foo_id');
 		$this->assertEqual($Sql_Join->render(), " JOIN bar ON foo.id=bar.foo_id");
	}
	
}
