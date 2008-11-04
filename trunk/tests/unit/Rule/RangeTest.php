<?php
require_once('A/DataContainer.php');
require_once('A/Rule/Range.php');

class Rule_RangeTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRuleRange() {
  		$dataspace = new A_DataContainer();

  		$rule = new A_Rule_Range(5, 10, 'test', 'error');

  		foreach (array(5,7,10) as $value) {
  			$dataspace->set('test', $value);
 			$result = $rule->isValid($dataspace);
			$this->assertTrue($result);
  		}
  		
		foreach (array(2,3,11,13) as $value) {
  			$dataspace->set('test', $value);
 			$result = $rule->isValid($dataspace);
			$this->assertFalse($result);
  		}
	}
	
}
