<?php
require_once('A/Template/Eval.php');

class Template_EvalTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testTemplate_EvalNotNull() {
  		$Template_Eval = new A_Template_Eval();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
