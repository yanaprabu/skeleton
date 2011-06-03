<?php

class Cli_ResponseTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testCli_ResponseNotNull() {
  		$Cli_Response = new A_Cli_Response();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
