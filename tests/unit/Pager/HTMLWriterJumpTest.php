<?php

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
