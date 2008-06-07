<?php
require_once('A/DataContainer.php');
require_once('A/Rule/Regexp.php');

class Rule_RegexpTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRuleRegexp() {
  		$dataspace = new A_DataContainer();
  		$rule = new A_Rule_Regexp('test', '/123$/', 'error');

  		$dataspace->set('test', 'test123');
  		$result = $rule->isValid($dataspace);
		$this->assertTrue($result);
		
  		$dataspace->set('test', 'test234');
 		$result = $rule->isValid($dataspace);
		$this->assertFalse($result);
	}
	
}
