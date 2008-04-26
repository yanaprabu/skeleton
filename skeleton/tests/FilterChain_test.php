<?php
require_once('A/FilterChain.php');
require_once('A/Filter/Tolower.php');

class FilterChainTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testValidatorFilterObject() {
  		$filterchain = new A_FilterChain();
 
		$filterchain->addFilter(new A_Filter_Tolower());
  		$foo = $filterchain->run('TEST123');
 		$bar = $filterchain->run('ABC!');

		$this->assertEqual($foo,'test123');
		$this->assertEqual($bar,'abc!');
	}
	
	function testValidatorFilterName() {
  		$filterchain = new A_FilterChain();
 
		// should load A_Filter_Length
		$filterchain->addFilter('length', 3);
  		$foo = $filterchain->run('TEST123');
 		$bar = $filterchain->run('ABC!');

		$this->assertEqual($foo,'TES');
		$this->assertEqual($bar,'ABC');
	}
	
	function testValidatorFilterFunction() {
  		$filterchain = new A_FilterChain();

 		$filterchain->addFilter('strtolower');
  		$foo = $filterchain->run('TEST123');
 		$bar = $filterchain->run('ABC!');

		$this->assertEqual($foo, 'test123');
		$this->assertEqual($bar, 'abc!');
	}

}
