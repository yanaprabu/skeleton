<?php

class Delimited_ReaderTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testDelimited_ReaderNoFile() {
 		$reader = new A_Delimited_Reader();
		$data = $reader->load();
 		
		$this->assertTrue($reader->isError());
		$this->assertTrue($reader->getErrorMsg() != '');
		$this->assertEqual($data, array());
	}
	
	function testDelimited_ReaderCsvFile() {
		$reader = new A_Delimited_Reader(dirname(__FILE__) . '/data1.csv');
		$reader->setFieldDelimiter(',');	// CSV
		$data = $reader->load();
dump($data);
	}
	
}
