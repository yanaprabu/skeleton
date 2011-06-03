<?php

class Html_Form_SubmitTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_SubmitRenderParams() {
		$obj = new A_Html_Form_Submit();
		$this->assertEqual($obj->render(), '<input type="submit"/>');
		$this->assertEqual($obj->render(array('name'=>'foo')), '<input name="foo" type="submit"/>');
	}
	
	function testHtml_Form_SubmitConstructParams() {
		$obj = new A_Html_Form_Submit(array('name'=>'foo'));
		$this->assertEqual($obj->render(), '<input name="foo" type="submit"/>');
		$obj = new A_Html_Form_Submit(array('name'=>'foo', 'value'=>'bar'));
		$this->assertEqual($obj->render(), '<input name="foo" value="bar" type="submit"/>');
	}
	
	function testHtml_Form_SubmitStaticParams() {
		$this->assertEqual(A_Html_Form_Submit::render(), '<input type="submit"/>');
		$this->assertEqual(A_Html_Form_Submit::render(array('name'=>'foo')), '<input name="foo" type="submit"/>');
	}
	
}
