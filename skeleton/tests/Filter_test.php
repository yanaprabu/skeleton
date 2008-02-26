<?php
require_once('A/FilterChain.php');
require_once('A/Filter/Toupper.php');
require_once('A/Filter/Tolower.php');
require_once('A/Filter/Regexp.php');
require_once('A/Filter/Length.php');

class FilterChainTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testFilterToUppper() {
  		$filter = new A_FilterChain();

  		$filter->addFilter(new A_Filter_Toupper());
  		$value = $filter->run('Test123');
		$this->assertEqual($value, 'TEST123');
	}
	
	function testFilterToLower() {
  		$filter = new A_FilterChain();

  		$filter->addFilter(new A_Filter_Tolower());
  		$value = $filter->run('TEST123');
		$this->assertEqual($value, 'test123');
	}
	
	function testFilterLength() {
  		$filter = new A_FilterChain();

  		$filter->addFilter(new A_Filter_Length(5));
  		$value = $filter->run('test123');
		$this->assertEqual($value, 'test1');
	}
	
	function testFilterRegexp() {
  		$filter = new A_FilterChain();

  		$filter->addFilter(new A_Filter_Regexp('/[^a-z]/'));
  		$value = $filter->run('test123');
		$this->assertEqual($value, 'test');
	}
	
	function testFilterMultiple() {
  		$filter = new A_FilterChain();

  		$filter->addFilter(new A_Filter_Toupper());
  		$value = $filter->run('test123');
		$this->assertEqual($value, 'TEST123');

  		$filter->addFilter(new A_Filter_Length(5));
  		$value = $filter->run('test123');
		$this->assertEqual($value, 'TEST1');

  		$filter->addFilter(new A_Filter_Regexp('/[^A-Z]/'));
  		$value = $filter->run('test123');
		$this->assertEqual($value, 'TEST');
	}
	
}
?>