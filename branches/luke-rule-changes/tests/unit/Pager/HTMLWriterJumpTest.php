<?php
require_once('A/Pager.php');
require_once('A/Pager/HTMLWriterJump.php');

class Pager_HTMLWriterJumpTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testPager_HTMLWriterJumpNotNull() {
  		$pager = null;
  		$Pager_HTMLWriterJump = new A_Pager_HTMLWriterJump($pager);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
