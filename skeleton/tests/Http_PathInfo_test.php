<?php
require_once('A/Http/PathInfo.php');

class Http_PathInfoTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHttp_PathInfoNotNull() {
  		$map = null;
  		$map_extra_param_pairs = true;
  		$Http_PathInfo = new A_Http_PathInfo($map, $map_extra_param_pairs);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
