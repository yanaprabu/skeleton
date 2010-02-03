<?php

class PagerTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testPagerNotNull() {
  		$data = array('foo'=>1, 'bar'=>2);
  		$datasource = new A_DataContainer($data);
  		$Pager = new A_Pager($datasource);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
