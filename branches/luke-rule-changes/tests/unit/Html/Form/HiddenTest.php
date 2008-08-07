<?php
require_once('A/Html/Form/Hidden.php');

class Html_Form_HiddenTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_HiddenRenderParams() {
		$obj = new A_Html_Form_Hidden();
		$this->assertEqual($obj->render(), '<input type="hidden" value=""/>');
		$this->assertEqual($obj->render(array('name'=>'foo')), '<input name="foo" type="hidden" value=""/>');
	}
	
	function testHtml_Form_HiddenConstructParams() {
		$obj = new A_Html_Form_Hidden(array('name'=>'foo'));
		$this->assertEqual($obj->render(), '<input name="foo" type="hidden" value=""/>');
		$obj = new A_Html_Form_Hidden(array('name'=>'foo', 'value'=>'bar'));
		$this->assertEqual($obj->render(), '<input name="foo" value="bar" type="hidden"/>');
	}
	
	function testHtml_Form_HiddenStaticParams() {
		$this->assertEqual(A_Html_Form_Hidden::render(), '<input type="hidden" value=""/>');
		$this->assertEqual(A_Html_Form_Hidden::render(array('name'=>'foo')), '<input name="foo" type="hidden" value=""/>');
		$this->assertEqual(A_Html_Form_Hidden::render(array('name'=>'foo', 'value'=>'bar')), '<input name="foo" value="bar" type="hidden"/>');
	}
	
}
