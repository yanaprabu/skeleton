<?php
require_once('A/Html/Form/Button.php');

class Html_Form_ButtonTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_ButtonRenderParams() {
		$obj = new A_Html_Form_Button();
		$this->assertEqual($obj->render(), '<input type="button" value=""/>');
		$this->assertEqual($obj->render(array('name'=>'foo')), '<input name="foo" type="button" value=""/>');
	}
	
	function testHtml_Form_ButtonConstructParams() {
		$obj = new A_Html_Form_Button(array('name'=>'foo'));
		$this->assertEqual($obj->render(), '<input name="foo" type="button" value=""/>');
		$obj = new A_Html_Form_Button(array('name'=>'foo', 'value'=>'bar'));
		$this->assertEqual($obj->render(), '<input name="foo" value="bar" type="button"/>');
	}
	
	function testHtml_Form_ButtonStaticParams() {
		$this->assertEqual(A_Html_Form_Button::render(), '<input type="button" value=""/>');
		$this->assertEqual(A_Html_Form_Button::render(array('name'=>'foo')), '<input name="foo" type="button" value=""/>');
		$this->assertEqual(A_Html_Form_Button::render(array('name'=>'foo', 'value'=>'bar')), '<input name="foo" value="bar" type="button"/>');
	}
	
}
