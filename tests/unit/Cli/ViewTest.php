<?php

class Cli_ViewTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testCli_ViewNotNull() {
  		$locator = new A_Locator();
  		$Cli_View = new A_Cli_View($locator);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
