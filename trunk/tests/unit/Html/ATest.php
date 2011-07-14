<?php

class Html_ATest extends UnitTestCase
{

	public function testConstructParams()
	{
		$obj = new A_Html_A();
		$this->assertEqual($obj->render(), '<a></a>');
		$obj = new A_Html_A(array('href'=>'foo'));
		$this->assertEqual($obj->render(), '<a href="foo"></a>');
		$obj = new A_Html_A(array(), 'foobar');
		$this->assertEqual($obj->render(), '<a>foobar</a>');
		$obj = new A_Html_A(array('href'=>'foo'), 'foobar');
		$this->assertEqual($obj->render(), '<a href="foo">foobar</a>');
	}
	
	public function testRenderParams()
	{
 		$obj = new A_Html_A();
		
 		$this->assertEqual($obj->render(), '<a></a>');
		$this->assertEqual($obj->render(array('href'=>'foo')), '<a href="foo"></a>');
		$this->assertEqual($obj->render(array(), 'foobar'), '<a>foobar</a>');
		$this->assertEqual($obj->render(array('href'=>'foo'), 'foobar'), '<a href="foo">foobar</a>');
	}
	
	public function testStaticRenderParams()
	{
		$this->assertEqual(A_Html_A::render(), '<a></a>');
		$this->assertEqual(A_Html_A::render(array('href'=>'foo')), '<a href="foo"></a>');
		$this->assertEqual(A_Html_A::render(array(), 'foobar'), '<a>foobar</a>');
		$this->assertEqual(A_Html_A::render(array('href'=>'foo'), 'foobar'), '<a href="foo">foobar</a>');
	}
	
	public function testMultiParams()
	{
		$obj = new A_Html_A();
		
		$this->assertEqual($obj->render(array('href'=>'http://example.com/','class'=>'sidebar'), 'foo'), '<a href="http://example.com/" class="sidebar">foo</a>');
	}
	
	public function testHrefArgument()
	{
		$obj = new A_Html_A();
		
		$this->assertEqual($obj->render(array(), 'foo', 'http://example.com/'), '<a href="http://example.com/">foo</a>');
	}

}
