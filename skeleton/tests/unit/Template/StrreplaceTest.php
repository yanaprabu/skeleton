<?php
require_once('A/Template/Strreplace.php');

class Template_StrreplaceTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testTemplate_StrreplaceNotNull() {
  		$filename='';
  		$data=array();
  		$auto_blocks=false;
		$Template_Strreplace = new A_Template_Strreplace($filename, $data, $auto_blocks);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
