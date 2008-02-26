<?php
require_once('A/DataContainer.php');
require_once('A/Validator.php');
require_once('A/Rule/Length.php');
require_once('A/Rule/Range.php');

class ValidatorTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testValidatorNotNull() {
  		$validator = new A_Validator();
  		$dataspace = new A_DataContainer();

   		$rule = new A_Rule_NotNull('test', 'error');
  		$validator->addRule($rule);
  		$result = $validator->validate($dataspace);
		$this->assertFalse($result);

  		$dataspace->set('test', 'test123');
  		$result = $validator->validate($dataspace);
		$this->assertTrue($result);
	}
	
	function testValidatorRegexp() {
  		$validator = new A_Validator();
  		$dataspace = new A_DataContainer();

  		$rule = new A_Rule_Regexp('test', '/123$/', 'error');
  		$validator->addRule($rule);
  		$dataspace->set('test', 'test123');
  		$result = $validator->validate($dataspace);
		$this->assertTrue($result);
		
  		$dataspace->set('test', 'test234');
 		$result = $validator->validate($dataspace);
		$this->assertFalse($result);
	}
	
	function testValidatorLength() {
  		$validator = new A_Validator();
  		$dataspace = new A_DataContainer();

  		$rule = new A_Rule_Length('test', 5, 10, 'error');
  		$validator->addRule($rule);

  		$dataspace->set('test', 'TEST123');
 		$result = $validator->validate($dataspace);
		$this->assertTrue($result);

  		$dataspace->set('test', 'TEST');
 		$result = $validator->validate($dataspace);
		$this->assertFalse($result);

  		$dataspace->set('test', 'TEST1234567890');
 		$result = $validator->validate($dataspace);
		$this->assertFalse($result);
	}
	
	function testValidatorRange() {
  		$validator = new A_Validator();
  		$dataspace = new A_DataContainer();

  		$rule = new A_Rule_Range('test', 5, 10, 'error');
  		$validator->addRule($rule);

  		$dataspace->set('test', 7);
 		$result = $validator->validate($dataspace);
		$this->assertTrue($result);

  		$dataspace->set('test', 2);
 		$result = $validator->validate($dataspace);
		$this->assertFalse($result);

  		$dataspace->set('test', 12);
 		$result = $validator->validate($dataspace);
		$this->assertFalse($result);
	}
	
}
?>