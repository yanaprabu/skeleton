<?php

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

		$config = new A_Collection(array('foo' => 'The value of foo.'));
		$locator->set('Config', $config);
		
		// load a class
		$inject = array(
				'Example1' => array(
					),
				'Example2' => array(
					'__construct' => array(
						0 => array(					// this param is the return from $locator->get('Example1', 'Example1')
							'A_Locator' => 'get',
							'name' => 'Example1',
							'class' => 'Example1',
							),
						),
					'setBoth' => array(
						0 => array(					// this param is the return from $locator->get('Config')->get('foo')
							'A_Locator' => 'container',
							'name' => 'Config',
							'class' => '',
							'key' => 'foo',
							),
						1 => array(
							'type' => 'string',
							'value' => 'Hello world.',
							),
						),
					),
				);
		$locator->register($inject);
		$example3 = $locator->get('example', 'Example2');
#dump($example3);
		// check if class directly loaded exists
		$this->assertTrue(class_exists('Example2'));
		// check if dependent class exists
		$this->assertTrue(class_exists('Example1'));

		// is object the correct type
		$this->assertTrue(is_a($example3, 'Example2'));
		
		$example4 = $example3->getArgs();	// get constructor args injected inot Example2
		$this->assertTrue(is_a($example4, 'Example1'));
	}
	
	function testLocatorClassLoader() {
		$locator = new A_Locator();
		$dir = dirname(__FILE__);

		// set directory for classes that use PEAR Foo_Bar style naming
		$locator->setDir("$dir/../include/", 'Foo');
		
		$result = $locator->loadClass('Foo_Bar');
		$this->assertTrue($result);
		$this->assertTrue(class_exists('Foo_Bar', false));

		$locator->setDir("$dir/../include/Foo", '/^Foo.*/');
		$result = $locator->loadClass('FooBar');
	}
	
	function testLocatorClassLoaderNS() {
		$locator = new A_Locator();
		$dir = dirname(__FILE__);

		// set directory for classes that use PEAR Foo_Bar style naming
		$locator->setDir("$dir/../include/", 'Foo');
		
		$FooBarNS = new \Foo\BarNS();
		dump($FooBarNS, '\Foo\BarNS: ', 1);
		$this->assertTrue(class_exists('\Foo\BarNS', false));
		
		$FooBarBazNS = new \Foo\Bar\BazNS();
		dump($FooBarBazNS, '\Foo\Bar\BazNS: ', 1);
		$this->assertTrue(class_exists('\Foo\Bar\BazNS', false));
	}
	
}

class LocatorExample {
	protected $value = '';
	
	function set($value) {
		$this->value = $value;
	}
	
	function get() {
		return $this->value;
	}
}
