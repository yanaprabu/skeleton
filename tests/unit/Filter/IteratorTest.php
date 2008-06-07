<?php
require_once('A/Filter/Iterator.php');

class TestFilter {

	function run($data) {
		return $data . 'X';
	}
}
	
class Filter_IteratorTest extends UnitTestCase {
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testFilter_IteratorNotNull() {
		$filter = new TestFilter();
  		$Filter_Iterator = new A_Filter_Iterator($filter);
		
		$data = array('foo', 'bar', 'baz');
		$Filter_Iterator->run($data);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
