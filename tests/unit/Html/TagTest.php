<?php

class Html_TagTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_TagBasic() {
  		$Html_Tag = new A_Html_Tag();
		
  		$this->assertEqual($Html_Tag->render('x'), '<x/>');
  		$attr = array('before'=>'foo', 'after'=>'bar', );
  		$this->assertEqual($Html_Tag->render('x', $attr), 'foo<x/>bar');
  		$attr['id'] = 'baz';
  		$this->assertEqual($Html_Tag->render('x', $attr), 'foo<x id="baz"/>bar');
	}
	
}
