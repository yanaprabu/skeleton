<?php

class Html_DocTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_DocEmpty() {
  		$Html_Doc = new A_Html_Doc();
		
  		$expect = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\n\"http://www.w3.org/TR/html4/loose.dtd\">\n<html>\n<head>\n</head>\n<body>\n</body>\n</html>\n";
  		$this->assertEqual($Html_Doc->render(), $expect);
	}
	
}
