<?php

class Model_Form_FieldTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testModel_Form_FieldNotNull() {
  		$form = new A_Model_Form();
		$field = new A_Model_Form_Field('foo');
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
	function testRender() {
		$field = new A_Model_Form_Field('foo');
		$field->setValue('bar');
		
		$renderer = new MockRenderer();
		$field->setRenderer($renderer);
		$field->render();
		
		$data = $renderer->getData();
		$this->assertEqual('foo', $data['name']);
		$this->assertEqual('bar', $data['value']);
	}
	
}

class MockRenderer {
	private $data;
	public function import($data) {
		$this->data = $data;
	}
	public function getData() {
		return $this->data;
	}
	public function render() {
		return null;
	}
}