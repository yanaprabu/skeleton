<?php
require_once('A/Html/Form/Checkbox.php');

class Html_Form_CheckboxTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_CheckboxRenderParams() {
		$obj = new A_Html_Form_Checkbox();
		$this->assertEqual($obj->render(), '<input type="checkbox" value=""/>');
		$this->assertEqual($obj->render(array('name'=>'foo')), '<input name="foo[]" type="checkbox" value=""/>');
	}
	
	function testHtml_Form_CheckboxConstructParams() {
		$obj = new A_Html_Form_Checkbox(array('name'=>'foo'));
		$this->assertEqual($obj->render(), '<input name="foo[]" type="checkbox" value=""/>');
		$obj = new A_Html_Form_Checkbox(array('name'=>'foo', 'value'=>'bar'));
		$this->assertEqual($obj->render(), '<input name="foo[]" value="bar" type="checkbox"/>bar');
	}
	
	function testHtml_Form_CheckboxStaticParams() {
		$this->assertEqual(A_Html_Form_Checkbox::render(), '<input type="checkbox" value=""/>');
		$this->assertEqual(A_Html_Form_Checkbox::render(array('name'=>'foo')), '<input name="foo[]" type="checkbox" value=""/>');
		$this->assertEqual(A_Html_Form_Checkbox::render(array('name'=>'foo', 'value'=>'bar')), '<input name="foo[]" type="checkbox" value="bar"/>bar');
	}
	
}
