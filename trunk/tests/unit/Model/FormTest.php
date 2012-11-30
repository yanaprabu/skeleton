<?php
class Model_FormTest_Request extends A_Http_Request
{
	public function setMethod($method) {
		$this->method = $method;
	}
}

class Model_FormTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testModel_FormSubmitted() {
		$request = new Model_FormTest_Request();
		$model = new A_Model_Form();
		
		// check not post or 
		$this->assertFalse($model->isSubmitted($request));
		
		// test submit param mode, post/get not required
		$request->set('submit', 'foo');
		$model->setMethod('');
		$model->setSubmitParameterName('submit');
		$this->assertTrue($model->isSubmitted($request));

		// test post mode
		$request->setMethod('POST');

		// post but no submit param name
		$model->setSubmitParameterName('');
		$this->assertTrue($model->isSubmitted($request));

		// post and submit param name
		$model->setSubmitParameterName('submit');
		$this->assertTrue($model->isSubmitted($request));
	}
	
	function testModel_FormValid() {
		$request = new Model_FormTest_Request();
		$model = new A_Model_Form();
		
		// check not post
		$this->assertFalse($model->isValid($request));
		
		// test post mode no fields or rules
		$request->setMethod('POST');
		$this->assertTrue($model->isValid($request));

		// test post mode with field and param value but no rules
		$request->set('foo', 'bar');
		$field = $model->newField('foo');
		$this->assertTrue($model->isValid($request));
		$this->assertEqual($model->get('foo'), 'bar');

		// add rule
		$field->addRule(new A_Rule_Notnull('foo', 'error'));
		
		// param not set
		$request->set('foo', null);
		$this->assertFalse($model->isValid($request));
		$this->assertTrue($model->isError());
		$this->assertEqual($model->getErrorMsg(), array('foo'=>array('error')));
		$this->assertEqual($model->getErrorMsg(' '), 'error');

		// param not set
		$request->set('foo', 'bar');
		$this->assertTrue($model->isValid($request));
	}
	
}
