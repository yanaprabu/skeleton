<?php
require_once('A/Pager/MySQL.php');

class Pager_MySQLTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testPager_MySQLNotNull() {
  		$query = 'SELECT * FROM foo WHERE bar=1';
  		$db = null;
		$Pager_MySQL = new A_Pager_MySQL($query, $db);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
