<?php

class Html_FormTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_FormNotNull() {
  		$obj = new A_Html_Form();
  		$obj->setAction('bar.php');
		
		$this->assertEqual($obj->render(array('name'=>'foo'), 'content'), '<form action="bar.php" method="post" name="foo">content</form>');
	}
	
}
