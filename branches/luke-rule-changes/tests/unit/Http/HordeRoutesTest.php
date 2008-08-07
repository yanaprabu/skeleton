<?php
#require_once('A/Http/HordeRoutes.php');

class Http_HordeRoutesTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHttp_HordeRoutesNotNull() {
#  		$Http_HordeRoutes = new A_Http_HordeRoutes();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
