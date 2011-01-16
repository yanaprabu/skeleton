<?php

class Rule_SetTest extends UnitTestCase {
	
    protected $data = array(
        'test' => '1234ambcAZAZAZ'
    );
    
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRule_SetRuleObject() {
		$dataspace = new A_DataContainer();

		$rules = new A_Rule_Set();
		//$dataspace = new A_DataContainer();

 		$rule = new A_Rule_Notnull(array('field' => 'test', 'errorMsg' => 'error'));
		$rules->addRule($rule);
		$result = $rules->isValid(array());
		$this->assertFalse($result);

		$result = $rules->isValid($this->data);
		$this->assertTrue($result);
	}
/*
	function testRule_SetRuleName() {
		$rules = new A_Rule_Set();
		$dataspace = new A_DataContainer();

		// should load A_Rule_Numeric
		$rules->addRule('numeric', 'test', 'not a number');

 		$dataspace->set('test', 'test123');
 		$this->assertFalse($rules->validate($dataspace));
 		$this->assertEqual($rules->getErrorMsg(), array(0=>'not a number'));
 		
 		$dataspace->set('test', '123');
		$this->assertTrue($rules->validate($dataspace));
 		$this->assertEqual($rules->getErrorMsg(), array());
		
 		$dataspace->set('test', 123);
		$this->assertTrue($rules->validate($dataspace));
	}*/

}