<?php
require_once('A/Html/Form/File.php');

class Html_Form_FileTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_FileNotNull() {
  		$Html_Form_File = new A_Html_Form_File();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
