<?php
require_once('A/Html/Form/Password.php');

class Html_Form_PasswordTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_PasswordRenderParams() {
		$obj = new A_Html_Form_Password();
		$this->assertEqual($obj->render(), '<input type="password" value=""/>');
		$this->assertEqual($obj->render(array('name'=>'foo')), '<input name="foo" type="password" value=""/>');
	}
	
	function testHtml_Form_PasswordConstructParams() {
		$obj = new A_Html_Form_Password(array('name'=>'foo'));
		$this->assertEqual($obj->render(), '<input name="foo" type="password" value=""/>');
		$obj = new A_Html_Form_Password(array('name'=>'foo', 'value'=>'bar'));
		$this->assertEqual($obj->render(), '<input name="foo" value="bar" type="password"/>');
	}
	
	function testHtml_Form_PasswordStaticParams() {
		$this->assertEqual(A_Html_Form_Password::render(), '<input type="password" value=""/>');
		$this->assertEqual(A_Html_Form_Password::render(array('name'=>'foo')), '<input name="foo" type="password" value=""/>');
		$this->assertEqual(A_Html_Form_Password::render(array('name'=>'foo', 'value'=>'bar')), '<input name="foo" value="bar" type="password"/>');
	}
	
}
