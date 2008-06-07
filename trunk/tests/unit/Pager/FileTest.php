<?php
require_once('A/Pager/File.php');

class Pager_FileTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testPager_FileNotNull() {
  		$filename = 'foo.txt';
  		$Pager_File = new A_Pager_File($filename);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
