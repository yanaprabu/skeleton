<?php

class A_Pagination_LinkTest extends UnitTestCase
{

	public function testSeparator()
	{
		$linkHelper = new A_Pagination_Helper_Link($this->createCore());
		
		$this->assertEqual($linkHelper->separator(), ' ');
		$linkHelper->setSeparator('foo');
		$this->assertEqual($linkHelper->separator(), 'foo');
	}
	
	public function testFirst()
	{
		$core = $this->createCore();
		$linkHelper = new A_Pagination_Helper_Link($core, new MockUrlHelper());
		
		$this->assertEqual($linkHelper->first('first'), '');
		$this->assertEqual($linkHelper->first(), '');
		
		$linkHelper->alwaysShowFirstLast(true);
		// space at end because default separator is a space
		$this->assertEqual($linkHelper->first('first'), '<a href="foo">first</a> ');
		$this->assertEqual($linkHelper->first(), '<a href="foo">1</a> ');
	}
	
	public function testLast()
	{
		$core = $this->createCore();
		$linkHelper = new A_Pagination_Helper_Link($core, new MockUrlHelper());
		$core->setCurrentPage(8);
		
		$this->assertEqual($linkHelper->last('last'), '');
		$this->assertEqual($linkHelper->last(), '');
		
		$linkHelper->alwaysShowFirstLast(true);
		// space at end because default separator is a space
		$this->assertEqual($linkHelper->last('last'), ' <a href="foo">last</a>');
		$this->assertEqual($linkHelper->last(), ' <a href="foo">8</a>');
	}
	
	public function testPrevious()
	{
		$core = $this->createCore();
		$linkHelper = new A_Pagination_Helper_Link($core, new MockUrlHelper());
		
		$core->setCurrentPage(1);
		$this->assertEqual($linkHelper->previous('previous'), '');
		$this->assertEqual($linkHelper->previous(), '');
		
		$linkHelper->alwaysShowPreviousNext(true);
		$this->assertEqual($linkHelper->previous('previous'), '<a href="foo">previous</a> ');
		$this->assertEqual($linkHelper->previous(), '<a href="foo">1</a> ');
		
		$core->setCurrentPage(8);
		$this->assertEqual($linkHelper->previous('previous'), '<a href="foo">previous</a> ');
		$this->assertEqual($linkHelper->previous(), '<a href="foo">7</a> ');
	}
	
	public function testNext()
	{
		$core = $this->createCore();
		$linkHelper = new A_Pagination_Helper_Link($core, new MockUrlHelper());
		
		$core->setCurrentPage(8);
		$this->assertEqual($linkHelper->next('next'), '');
		$this->assertEqual($linkHelper->next(), '');
		
		$linkHelper->alwaysShowPreviousNext(true);
		$this->assertEqual($linkHelper->next('next'), ' <a href="foo">next</a>');
		$this->assertEqual($linkHelper->next(), ' <a href="foo">8</a>');
		
		$core->setCurrentPage(1);
		$this->assertEqual($linkHelper->next('next'), ' <a href="foo">next</a>');
		$this->assertEqual($linkHelper->next(), ' <a href="foo">2</a>');
	}
	
	public function testRange()
	{
		$core = $this->createCore();
		$linkHelper = new A_Pagination_Helper_Link($core, new MockUrlHelper());
		
		$core->setCurrentPage(1);
		$this->assertEqual($linkHelper->range(), '1 <a href="foo">2</a> <a href="foo">3</a> <a href="foo">4</a> <a href="foo">5</a>');
		
		$core->setCurrentPage(8);
		$this->assertEqual($linkHelper->range(), '<a href="foo">4</a> <a href="foo">5</a> <a href="foo">6</a> <a href="foo">7</a> 8');
		
		$core->setCurrentPage(4);
		$this->assertEqual($linkHelper->range(), '<a href="foo">1</a> <a href="foo">2</a> <a href="foo">3</a> 4 <a href="foo">5</a> <a href="foo">6</a> <a href="foo">7</a> <a href="foo">8</a>');
		
		$core->setRangeSize(1);
		$this->assertEqual($linkHelper->range(), '<a href="foo">3</a> 4 <a href="foo">5</a>');
		
		$core->setRangeSize(0);
		$this->assertEqual($linkHelper->range(), '4');
	}
	
	public function testFirstWithTemplateRenderer()
	{
		$core = $this->createCore();
		$linkHelper = new A_Pagination_Helper_Link($core, new MockUrlHelper());
		
		$template = new A_Template_Strreplace(__DIR__ . '/a_template.txt');
		$linkHelper->setRenderer($template);
		$linkHelper->alwaysShowFirstLast(true);
		
		$this->assertEqual($linkHelper->first('baz'), '<a href="foo" class="bar">baz</a> ');
	}
	
	public function testFirstWithHtmlRenderer()
	{
		$core = $this->createCore();
		$linkHelper = new A_Pagination_Helper_Link($core, new MockUrlHelper());
		
		$core->setCurrentPage(4);
		$core->setRangeSize(1);
		
		$tag = new A_Html_A(array('class' => 'foobar'));
		$linkHelper->setRenderer($tag);
		
		$this->assertEqual($linkHelper->range(), '<a class="foobar" href="foo">3</a> 4 <a class="foobar" href="foo">5</a>');
	}
	
	public function testRangeWithHtmlRenderer()
	{
		$core = $this->createCore();
		$linkHelper = new A_Pagination_Helper_Link($core, new MockUrlHelper());
		
		$tag = new A_Html_A(array('class' => 'foobar'));
		$linkHelper->setRenderer($tag);
		$linkHelper->alwaysShowFirstLast(true);
		
		$this->assertEqual($linkHelper->first('baz'), '<a class="foobar" href="foo">baz</a> ');
	}
	
	private function createCore()
	{
		return new A_Pagination_Request(new A_Pagination_Adapter_Array(array(
				array('id'=>1, 'name'=>'One', 'color'=>'blue'),
				array('id'=>2, 'name'=>'Two', 'color'=>'red'),
				array('id'=>3, 'name'=>'Three', 'color'=>'green'),
				array('id'=>4, 'name'=>'Four', 'color'=>'blue'),
				array('id'=>5, 'name'=>'Five', 'color'=>'blue'),
				array('id'=>6, 'name'=>'Six', 'color'=>'black'),
				array('id'=>7, 'name'=>'Seven', 'color'=>'green'),
				array('id'=>8, 'name'=>'Eight', 'color'=>'blue'),
		)), 1);
	}

}

class MockUrlHelper
{

	public function render()
	{
		return 'foo';
	}
	
	public function set(){}

}
