<?php

class Html_Form_RadioTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_RadioRenderParams() {
		$obj = new A_Html_Form_Radio();
		$this->assertEqual($obj->render(), '<input type="radio" value=""/>');
		$this->assertEqual($obj->render(array('name'=>'foo')), '<input name="foo" type="radio" value=""/>');
	}
	
	function testHtml_Form_RadioConstructParams() {
		$obj = new A_Html_Form_Radio(array('name'=>'foo'));
		$this->assertEqual($obj->render(), '<input name="foo" type="radio" value=""/>');
		$obj = new A_Html_Form_Radio(array('name'=>'foo', 'value'=>'bar'));
		$this->assertEqual($obj->render(), '<input name="foo" type="radio" value="bar"/>bar');
	}
	
	function testHtml_Form_RadioStaticParams() {
		$this->assertEqual(A_Html_Form_Radio::render(), '<input type="radio" value=""/>');
		$this->assertEqual(A_Html_Form_Radio::render(array('name'=>'foo')), '<input name="foo" type="radio" value=""/>');
		$this->assertEqual(A_Html_Form_Radio::render(array('name'=>'foo', 'value'=>'bar')), '<input name="foo" type="radio" value="bar"/>bar');
	}
	
}
