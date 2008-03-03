<?php
require_once('A/Db/SqLite.php');

class Db_SqLiteTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testDb_SqLiteNotNull() {
  		$Db_SqLite = new A_Db_SqLite();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
