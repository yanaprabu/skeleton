<?php

class Cli_ViewTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testCli_ViewNotNull() {
		$locator = new A_Locator();
		$Cli_View = new A_Cli_View($locator);
		
		$this->assertEqual($Cli_View->render(), '');
			
		$str = 'Hello world!';
		$Cli_View->setContent($str);
		$this->assertEqual($Cli_View->render(), $str);
			
		$Cli_View->setTemplatePath(dirname(__FILE__) . '/templates');		// needed because ../menu.php runs this script
		$Cli_View->setTemplate('test1');
		$this->assertEqual($Cli_View->render(), "foo\nbar\n");
	}
	
}
