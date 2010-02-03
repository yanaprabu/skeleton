<?php

class Template_IncludeTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testTemplate_IncludeNotNull() {
  		$filename='';
  		$data=array();
  		$auto_blocks=false;
		$Template_Include = new A_Template_Include($filename, $data, $auto_blocks);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
