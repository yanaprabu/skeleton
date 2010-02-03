<?php

class Html_Form_FieldsetTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_FieldsetObjConstructParams() {
		
		$obj = new A_Html_Form_Fieldset();
		$this->assertEqual($obj->render(), '<fieldset></fieldset>');
		$obj = new A_Html_Form_Fieldset(array('name'=>'foo'));
		$this->assertEqual($obj->render(), '<fieldset name="foo"></fieldset>');
		$obj = new A_Html_Form_Fieldset(array(), 'foobar');
		$this->assertEqual($obj->render(), '<fieldset>foobar</fieldset>');
		$obj = new A_Html_Form_Fieldset(array('name'=>'foo'), 'foobar');
		$this->assertEqual($obj->render(), '<fieldset name="foo">foobar</fieldset>');
	}
	
	function testHtml_Form_FieldsetObjRenderParams() {
 		$obj = new A_Html_Form_Fieldset();

 		$this->assertEqual($obj->render(), '<fieldset></fieldset>');
		$this->assertEqual($obj->render(array('name'=>'foo')), '<fieldset name="foo"></fieldset>');
		$this->assertEqual($obj->render(array(), 'foobar'), '<fieldset>foobar</fieldset>');
		$this->assertEqual($obj->render(array('name'=>'foo'), 'foobar'), '<fieldset name="foo">foobar</fieldset>');
	}
	
	function testHtml_Form_FieldsetStaticRenderParams() {
		$this->assertEqual(A_Html_Form_Fieldset::render(), '<fieldset></fieldset>');
		$this->assertEqual(A_Html_Form_Fieldset::render(array('name'=>'foo')), '<fieldset name="foo"></fieldset>');
		$this->assertEqual(A_Html_Form_Fieldset::render(array(), 'foobar'), '<fieldset>foobar</fieldset>');
		$this->assertEqual(A_Html_Form_Fieldset::render(array('name'=>'foo'), 'foobar'), '<fieldset name="foo">foobar</fieldset>');
	}

}
