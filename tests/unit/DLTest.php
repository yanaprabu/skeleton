<?php

class DLTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testDLNotNull() {
		$locator = new A_Locator();
  		$example = new A_DL('', 'Example1', 'one', 'hello world');

		$obj = $example->run($locator);
		$this->assertFalse($obj === $example);

		$obj2 = $example->run($locator);
		$this->assertTrue($obj === $obj2);
	}
	
/*
	function testDLArgs() {
  		$example = new A_DL('Example1.php', 'Example1', 'one', 'hello world');

		$a = new Example1();
		$a->value = 'one';
		$obj = DL::resolve($example);
		$this->assertFalse($obj === $example);

		$obj2 = DL::resolve($obj);
		$this->assertTrue($obj === $obj2);

	}
*/
	
}
?>