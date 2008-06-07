<?php
require_once('A/Template/File.php');

class Template_FileTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testTemplate_FileNotNull() {
  		$filename='';
  		$data=array();
  		$auto_blocks=false;
  		$Template_File = new A_Template_File($filename, $data, $auto_blocks);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
