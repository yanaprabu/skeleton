<?php
require_once('A/DataContainer.php');
require_once('A/Validator.php');
require_once('A/Rule/Length.php');
require_once('A/Rule/Notnull.php');
require_once('A/Rule/Range.php');

class ValidatorTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testValidatorRuleObject() {
  		$validator = new A_Validator();
  		$dataspace = new A_DataContainer();

   		$rule = new A_Rule_Notnull('test', 'error');
  		$validator->addRule($rule);
  		$result = $validator->validate($dataspace);
		$this->assertFalse($result);

  		$dataspace->set('test', 'test123');
  		$result = $validator->validate($dataspace);
		$this->assertTrue($result);
	}

	function testValidatorRuleName() {
  		$validator = new A_Validator();
  		$dataspace = new A_DataContainer();

		// should load A_Rule_Numeric
  		$validator->addRule('numeric', 'test', 'not a number');

 		$dataspace->set('test', 'test123');
 		$this->assertFalse($validator->validate($dataspace));
 		$this->assertEqual($validator->getErrorMsg(), array(0=>'not a number'));
 		
 		$dataspace->set('test', '123');
		$this->assertTrue($validator->validate($dataspace));
 		$this->assertEqual($validator->getErrorMsg(), array());
		
 		$dataspace->set('test', 123);
		$this->assertTrue($validator->validate($dataspace));
	}

}