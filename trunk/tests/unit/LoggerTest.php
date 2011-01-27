<?php

class LoggerTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testLoggerDefault() {
  		$writer = dirname(__FILE__) . '/Logger/LoggerTest.log';
#echo "WRITER=$writer<br/>";
  		$logger = new A_Logger($writer);
		$logger->clear();
  		
		$logger->log('one');
		$logger->log('two');
		$logger->write();
		
  		$this->assertTrue(file_exists($writer));
		$str = file_get_contents($writer);
#echo "LOG=<pre>$str</pre>";
		$this->assertTrue(preg_match('/[0-9\:\-\ ]* - one\n[0-9\:\-\ ]* - two\n/', $str));
	}
	
	function testLoggerLevel0() {
  		$writer = dirname(__FILE__) . '/Logger/LoggerTest.log';
  		$logger = new A_Logger($writer);
		$logger->clear();
  		
		$logger->log('one', 1);
		$logger->write();
		
  		$this->assertTrue(file_exists($writer));
		$str = file_get_contents($writer);
		$this->assertEqual('', $str);
#echo "LOG=<pre>$str</pre>";
	}
	
	function testLoggerLevel1() {
  		$writer = dirname(__FILE__) . '/Logger/LoggerTest.log';
  		$logger = new A_Logger($writer);
		$logger->clear();
  		
		$logger->log('one', 1);
		$logger->write(1);
		
  		$this->assertTrue(file_exists($writer));
		$str = file_get_contents($writer);
		$this->assertTrue(preg_match('/[0-9\:\-\ ]* - one\n/', $str));
	}
	
	function testLoggerSetLevel1() {
  		$writer = dirname(__FILE__) . '/Logger/LoggerTest.log';
  		$logger = new A_Logger($writer);
		$logger->clear();
		$logger->setLevel(1);
		
		$logger->log('one', 1);
		$logger->write();
		
  		$this->assertTrue(file_exists($writer));
		$str = file_get_contents($writer);
#echo "LOG=<pre>$str</pre>";
		$this->assertTrue(preg_match('/[0-9\:\-\ ]* - one\n/', $str));
	}
	
}
