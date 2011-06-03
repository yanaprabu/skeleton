<?php

class Pager_ADODBTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testPager_ADODBNotNull() {
  		$query = 'SELECT * FROM foo WHERE bar=1';
  		$db = null;
  		$Pager_ADODB = new A_Pager_ADODB($query, $db);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
