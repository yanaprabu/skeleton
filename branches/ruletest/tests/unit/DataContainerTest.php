<?php

class DataContainerExample {
	protected $value;
	function Test($value='not set') {
		$this->value = $value;
	}
}

class DataContainerTest extends UnitTestCase {
	
	function SetUp() {
	}
	
	function TearDown() {
	}
	
	function testDataContainerSetGet() {
  		$dataspace = new A_Collection();

   		$dataspace->set('test1', 'abc');
  		$value1 = $dataspace->get('test1');
		$this->assertTrue($value1 == 'abc');

	}
	
/*
	function testDataContainerSetRefGetRef() {
  		$dataspace = new A_Collection();
  		$example = new DataContainerExample('abc');

		$dataspace->set('test', $example);
  		$example2 = $dataspace->get('test');
		if (version_compare(PHP_VERSION, '5.0.0', '<')) {
echo 'DataContainer: Checking PHP4';
			$this->assertFalse($example == $example2);
		} else {
echo 'DataContainer: Checking PHP5';
			$this->assertTrue($example == $example2);
		}

   		$dataspace->setRef('test', $example);
  		$example2 = $dataspace->getRef('test');
		$this->assertTrue($example === $example2);
	}
*/
	
	function testDataContainerHas() {
  		$dataspace = new A_Collection();

   		$dataspace->set('test1', 'abc');
  		$result = $dataspace->has('test1');
		$this->assertTrue($result);

  		$result = $dataspace->has('test2');
		$this->assertFalse($result);
	}
	
}
?>