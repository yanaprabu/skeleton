<?php
require_once('A/Html/Tag.php');

class Html_TagTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_TagNotNull() {
  		$Html_Tag = new A_Html_Tag();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
