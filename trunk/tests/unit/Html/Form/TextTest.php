<?php
require_once('A/Html/Form/Text.php');

class Html_Form_TextTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_TextRenderParams() {
		$obj = new A_Html_Form_Text();
		$this->assertEqual($obj->render(), '<input type="text" value=""/>');
		$this->assertEqual($obj->render(array('name'=>'foo')), '<input name="foo" type="text" value=""/>');
	}
	
	function testHtml_Form_TextConstructParams() {
		$obj = new A_Html_Form_Text(array('name'=>'foo'));
		$this->assertEqual($obj->render(), '<input name="foo" type="text" value=""/>');
		$obj = new A_Html_Form_Text(array('name'=>'foo', 'value'=>'bar'));
		$this->assertEqual($obj->render(), '<input name="foo" value="bar" type="text"/>');
	}
	
	function testHtml_Form_TextStaticParams() {
		$this->assertEqual(A_Html_Form_Text::render(), '<input type="text" value=""/>');
		$this->assertEqual(A_Html_Form_Text::render(array('name'=>'foo')), '<input name="foo" type="text" value=""/>');
		$this->assertEqual(A_Html_Form_Text::render(array('name'=>'foo', 'value'=>'bar')), '<input name="foo" value="bar" type="text"/>');
	}
	
}
