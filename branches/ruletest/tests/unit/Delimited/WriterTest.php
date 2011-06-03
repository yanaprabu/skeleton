<?php

class Delimited_WriterTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testDelimited_WriterNoFile() {
 		$Writer = new A_Delimited_Writer();
		$data = array();
		$Writer->save($data);
		
		$this->assertTrue($Writer->isError());
		$this->assertTrue($Writer->getErrorMsg() != '');
		$this->assertEqual($data, array());
	}
	
	function testDelimited_WriterCsvFile() {
		$reader = new A_Delimited_Reader(dirname(__FILE__) . '/data1.csv');
		$reader->setFieldDelimiter(',');	// CSV
		$data = $reader->load();
#echo $Writer->getErrorMsg();
#dump($data);

		$writer = new A_Delimited_Writer(dirname(__FILE__) . '/data2.csv');
		$writer->setFieldDelimiter(',');	// CSV
		$writer->setWriteAllEnclosed(true);	// quote all field values
		$writer->save($data);
		// call_user_func style
//		$this->assertEqual($Writer->setFilters(array('/[^a-f]/', $toupper))->get('bar'), 'FA');
	}

}
