<?php
require_once('A/DataContainer.php');
require_once('A/Rule/Length.php');

class Rule_LengthTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRuleLength() {
  		$dataspace = new A_DataContainer();

  		$rule = new A_Rule_Length('test', 5, 10, 'error');
 
  		$dataspace->set('test', 'TEST123');
 		$result = $rule->isValid($dataspace);
		$this->assertTrue($result);

  		$dataspace->set('test', 'TEST');
 		$result = $rule->isValid($dataspace);
		$this->assertFalse($result);

  		$dataspace->set('test', 'TEST1234567890');
 		$result = $rule->isValid($dataspace);
		$this->assertFalse($result);
	}
	
}
