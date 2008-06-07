<?php
require_once('A/Html/Span.php');

class Html_SpanTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_SpanObjConstructParams() {
		
		$obj = new A_Html_Span();
		$this->assertEqual($obj->render(), '<span></span>');
		$obj = new A_Html_Span(array('name'=>'foo'));
		$this->assertEqual($obj->render(), '<span name="foo"></span>');
		$obj = new A_Html_Span(array(), 'foobar');
		$this->assertEqual($obj->render(), '<span>foobar</span>');
		$obj = new A_Html_Span(array('name'=>'foo'), 'foobar');
		$this->assertEqual($obj->render(), '<span name="foo">foobar</span>');
	}
	
	function testHtml_SpanObjRenderParams() {
 		$obj = new A_Html_Span();

 		$this->assertEqual($obj->render(), '<span></span>');
		$this->assertEqual($obj->render(array('name'=>'foo')), '<span name="foo"></span>');
		$this->assertEqual($obj->render(array(), 'foobar'), '<span>foobar</span>');
		$this->assertEqual($obj->render(array('name'=>'foo'), 'foobar'), '<span name="foo">foobar</span>');
	}
	
	function testHtml_SpanStaticRenderParams() {
		$this->assertEqual(A_Html_Span::render(), '<span></span>');
		$this->assertEqual(A_Html_Span::render(array('name'=>'foo')), '<span name="foo"></span>');
		$this->assertEqual(A_Html_Span::render(array(), 'foobar'), '<span>foobar</span>');
		$this->assertEqual(A_Html_Span::render(array('name'=>'foo'), 'foobar'), '<span name="foo">foobar</span>');
	}
	
}
