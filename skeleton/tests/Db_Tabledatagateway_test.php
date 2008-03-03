<?php
require_once('A/Db/Tabledatagateway.php');

class Db_TabledatagatewayTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testDb_TabledatagatewayNotNull() {
  		$db = null;
  		$table = 'foo';
  		$key = 'id';
  		$Db_Tabledatagateway = new A_Db_Tabledatagateway($db, $table, $key);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
