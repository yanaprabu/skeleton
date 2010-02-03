<?php

class Html_Form_ResetTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_ResetRenderParams() {
		$obj = new A_Html_Form_Reset();
		$this->assertEqual($obj->render(), '<input type="reset"/>');
		$this->assertEqual($obj->render(array('name'=>'foo')), '<input name="foo" type="reset"/>');
	}
	
	function testHtml_Form_ResetConstructParams() {
		$obj = new A_Html_Form_Reset(array('name'=>'foo'));
		$this->assertEqual($obj->render(), '<input name="foo" type="reset"/>');
		$obj = new A_Html_Form_Reset(array('name'=>'foo', 'value'=>'bar'));
		$this->assertEqual($obj->render(), '<input name="foo" value="bar" type="reset"/>');
	}
	
	function testHtml_Form_ResetStaticParams() {
		$this->assertEqual(A_Html_Form_Reset::render(), '<input type="reset"/>');
		$this->assertEqual(A_Html_Form_Reset::render(array('name'=>'foo')), '<input name="foo" type="reset"/>');
	}
	
}
