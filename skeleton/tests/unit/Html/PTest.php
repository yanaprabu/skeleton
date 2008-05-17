<?php
require_once('A/Html/P.php');

class Html_PTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_PObjConstructParams() {
		
		$obj = new A_Html_P();
		$this->assertEqual($obj->render(), '<p></p>');
		$obj = new A_Html_P(array('name'=>'foo'));
		$this->assertEqual($obj->render(), '<p name="foo"></p>');
		$obj = new A_Html_P(array(), 'foobar');
		$this->assertEqual($obj->render(), '<p>foobar</p>');
		$obj = new A_Html_P(array('name'=>'foo'), 'foobar');
		$this->assertEqual($obj->render(), '<p name="foo">foobar</p>');
	}
	
	function testHtml_PObjRenderParams() {
 		$obj = new A_Html_P();

 		$this->assertEqual($obj->render(), '<p></p>');
		$this->assertEqual($obj->render(array('name'=>'foo')), '<p name="foo"></p>');
		$this->assertEqual($obj->render(array(), 'foobar'), '<p>foobar</p>');
		$this->assertEqual($obj->render(array('name'=>'foo'), 'foobar'), '<p name="foo">foobar</p>');
	}
	
	function testHtml_PStaticRenderParams() {
		$this->assertEqual(A_Html_P::render(), '<p></p>');
		$this->assertEqual(A_Html_P::render(array('name'=>'foo')), '<p name="foo"></p>');
		$this->assertEqual(A_Html_P::render(array(), 'foobar'), '<p>foobar</p>');
		$this->assertEqual(A_Html_P::render(array('name'=>'foo'), 'foobar'), '<p name="foo">foobar</p>');
	}
	
	}
