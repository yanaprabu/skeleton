<?php
require_once('A/Html/Form/Textarea.php');

class Html_Form_TextareaTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_TextareaObjConstructParams() {
		
		$obj = new A_Html_Form_Textarea();
		$this->assertEqual($obj->render(), '<textarea></textarea>');
		$obj = new A_Html_Form_Textarea(array('name'=>'foo'));
		$this->assertEqual($obj->render(), '<textarea name="foo"></textarea>');
		$obj = new A_Html_Form_Textarea(array(), 'foobar');
		$this->assertEqual($obj->render(), '<textarea>foobar</textarea>');
		$obj = new A_Html_Form_Textarea(array('name'=>'foo'), 'foobar');
		$this->assertEqual($obj->render(), '<textarea name="foo">foobar</textarea>');
	}
	
	function testHtml_Form_TextareaObjRenderParams() {
 		$obj = new A_Html_Form_Textarea();

 		$this->assertEqual($obj->render(), '<textarea></textarea>');
		$this->assertEqual($obj->render(array('name'=>'foo')), '<textarea name="foo"></textarea>');
		$this->assertEqual($obj->render(array(), 'foobar'), '<textarea>foobar</textarea>');
		$this->assertEqual($obj->render(array('name'=>'foo'), 'foobar'), '<textarea name="foo">foobar</textarea>');
	}
	
	function testHtml_Form_TextareaStaticRenderParams() {
		$this->assertEqual(A_Html_Form_Textarea::render(), '<textarea></textarea>');
		$this->assertEqual(A_Html_Form_Textarea::render(array('name'=>'foo')), '<textarea name="foo"></textarea>');
		$this->assertEqual(A_Html_Form_Textarea::render(array(), 'foobar'), '<textarea>foobar</textarea>');
		$this->assertEqual(A_Html_Form_Textarea::render(array('name'=>'foo'), 'foobar'), '<textarea name="foo">foobar</textarea>');
	}
	
	}
