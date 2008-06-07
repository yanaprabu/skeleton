<?php
require_once('A/Html/Form/Label.php');

class Html_Form_LabelTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_LabelObjConstructParams() {
		
		$obj = new A_Html_Form_Label();
		$this->assertEqual($obj->render(), '<label></label>');
		$obj = new A_Html_Form_Label(array('for'=>'foo'));
		$this->assertEqual($obj->render(), '<label for="foo"></label>');
		$obj = new A_Html_Form_Label(array(), 'foobar');
		$this->assertEqual($obj->render(), '<label>foobar</label>');
		$obj = new A_Html_Form_Label(array('for'=>'foo'), 'foobar');
		$this->assertEqual($obj->render(), '<label for="foo">foobar</label>');
	}
	
	function testHtml_Form_LabelObjRenderParams() {
 		$obj = new A_Html_Form_Label();

 		$this->assertEqual($obj->render(), '<label></label>');
		$this->assertEqual($obj->render(array('for'=>'foo')), '<label for="foo"></label>');
		$this->assertEqual($obj->render(array(), 'foobar'), '<label>foobar</label>');
		$this->assertEqual($obj->render(array('for'=>'foo'), 'foobar'), '<label for="foo">foobar</label>');
	}
	
	function testHtml_Form_LabelStaticRenderParams() {
		$this->assertEqual(A_Html_Form_Label::render(), '<label></label>');
		$this->assertEqual(A_Html_Form_Label::render(array('for'=>'foo')), '<label for="foo"></label>');
		$this->assertEqual(A_Html_Form_Label::render(array(), 'foobar'), '<label>foobar</label>');
		$this->assertEqual(A_Html_Form_Label::render(array('for'=>'foo'), 'foobar'), '<label for="foo">foobar</label>');
	}
		
}
