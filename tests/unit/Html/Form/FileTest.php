<?php

class Html_Form_FileTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_Form_FileRenderParams() {
		$obj = new A_Html_Form_File();
		$this->assertEqual($obj->render(), '<input type="file"/>');
		$this->assertEqual($obj->render(array('name'=>'foo')), '<input name="foo" type="file"/>');
	}
	
	function testHtml_Form_FileConstructParams() {
		$obj = new A_Html_Form_File(array('name'=>'foo'));
		$this->assertEqual($obj->render(), '<input name="foo" type="file"/>');
		$obj = new A_Html_Form_File(array('name'=>'foo', 'value'=>'bar'));
		$this->assertEqual($obj->render(), '<input name="foo" value="bar" type="file"/>');
	}
	
	function testHtml_Form_FileStaticParams() {
		$this->assertEqual(A_Html_Form_File::render(), '<input type="file"/>');
		$this->assertEqual(A_Html_Form_File::render(array('name'=>'foo')), '<input name="foo" type="file"/>');
	}

}
