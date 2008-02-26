<?php
require_once('A/Locator.php');
require_once('A/DataContainer.php');

class LocatorExample {
var $value = '';

function set($value) {
	$this->value = $value;
}

function get() {
	return $this->value;
}

}

class LocatorTest extends UnitTestCase {
	
	function SetUp() {
	}
	
	function TearDown() {
	}
	
	function testLocatorSetGet() {
		$locator = new A_Locator();
		$example = new LocatorExample();

 		$example->set('abc');
		$value1 = $example->get();
		$this->assertTrue($value1 == 'abc');

		$locator->set('test', $example);
		$example2 = $locator->get('test');
  	$example2->set('xyz');
		$this->assertTrue($example === $example2);
		$this->assertTrue($example->get() == 'xyz');

	}
	
	function testLocatorGetLoad() {
		$locator = new A_Locator();
		// load a class
		$example3 = $locator->get('example', 'Example1');
		$this->assertTrue(class_exists('Example1'));
		$this->assertTrue(is_a($example3, 'Example1'));
	}
	
	function testLocatorGetLoadDI() {
		$locator = new A_Locator();

		$config = new A_DataContainer(array('foo' => 'The value of foo.'));
		$locator->set('Config', $config);
		
		// load a class
		$inject = array(
				'Example1' => array(
					),
				'Example2' => array(
					'__construct' => array(
						0 => array(
							'type' => 'class',
							'value' => 'Example1',
							),
						),
					'setBoth' => array(
						0 => array(
							'type' => 'locator',
							'name' => 'Config',
							'value' => 'foo',
							),
						1 => array(
							'type' => 'string',
							'value' => 'Hello world.',
							),
						),
					),
				);
		$locator->register($inject);
		$example3 = $locator->get('', 'Example2');
dump($example3);
		$this->assertTrue(class_exists('Example2'));
		$this->assertTrue(is_a($example3, 'Example2'));
	}
	
}
?>