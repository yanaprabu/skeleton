<?php

class Pager_DbTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testPager_DBNotNull() {
  		$query = 'SELECT * FROM foo WHERE bar=1';
  		$db = null;
		$Pager_DB = new A_Pager_DB($query, $db);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
