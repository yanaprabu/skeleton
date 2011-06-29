<?php

class Template extends A_Template_Base {

	public function getForTesting($name)
	{
		return $this->$name;
	}
	
	public function render()
	{
		
	}
}

class TemplateTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testTemplateNoConstructArgs() {
		$Template = new Template();
		
		$this->assertEqual($Template->getForTesting('filename'), '');
		$this->assertEqual($Template->getForTesting('data'), array());
	}
	
	function testTemplateConstructArgs() {
		$args = array('bar'=>7);
		$Template = new Template('foo', $args);
		
		// check if filename and data set
		$this->assertEqual($Template->getForTesting('filename'), 'foo');
		$this->assertEqual($Template->getForTesting('data'), $args);
		$this->assertEqual($Template->get('bar'), 7);
		// manually set data to see change
		$this->assertEqual($Template->setFilename('baz')->getForTesting('filename'), 'baz');
		$this->assertEqual($Template->set('bar', 9)->get('bar'), 9);
	}
	
}
