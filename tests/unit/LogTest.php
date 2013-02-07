<?php

class LogTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testLogDefault() {
  		$writer = dirname(__FILE__) . '/Log/LogTest.log';
#echo "WRITER=$writer<br/>";
		$logger = new A_Log($writer);
		$logger->clear();
  		
		$logger->log('one');
		$logger->log('two');
		$logger->write();
		
  		$this->assertTrue(file_exists($writer));
		$str = file_get_contents($writer);
#echo "LOG=<pre>$str</pre>";
		$this->assertTrue(preg_match('/[0-9\:\-\ ]* - one\r\n[0-9\:\-\ ]* - two\r\n/', $str));

		$logger->clear();
		$this->assertFalse(file_exists($writer));
	}
	
	function testLogLevel0() {
  		$writer = dirname(__FILE__) . '/Log/LogTest.log';
  		$logger = new A_Log($writer);
		$logger->clear();
  		
		$logger->log(1, 'one');
#echo "logger=<pre>" . print_r($logger, 1) . "</pre>";
		$logger->write(2);
		
  		$this->assertTrue(file_exists($writer));
		$str = file_get_contents($writer);
		$this->assertEqual('', $str);
#echo "LOG=<pre>" . print_r($str, 1) . "</pre>";
	}
	
	function testLogLevel1() {
  		$writer = dirname(__FILE__) . '/Log/LogTest.log';
  		$logger = new A_Log($writer);
		$logger->clear();
  		
		$logger->log(1, 'one');
#echo "logger=<pre>" . print_r($logger, 1) . "</pre>";
		$logger->write(1);
		
  		$this->assertTrue(file_exists($writer));
		$str = file_get_contents($writer);
#echo "LOG=<pre>$str</pre>";
		$this->assertTrue(preg_match('/[0-9\:\-\ ]* - one\r\n/', $str));
	}
	
	function testLogSetLevel1() {
  		$writer = dirname(__FILE__) . '/Log/LogTest.log';
  		$logger = new A_Log($writer);
		$logger->clear();
		$logger->setLevel(1);
		
		$logger->log('one', 1);
		$logger->write();
		
  		$this->assertTrue(file_exists($writer));
		$str = file_get_contents($writer);
#echo "LOG=<pre>$str</pre>";
		$this->assertTrue(preg_match('/[0-9\:\-\ ]* - one\r\n/', $str));
	}
	
	function testLogAutoWrite() {
  		$writer = dirname(__FILE__) . '/Log/LogTest.log';
  		$logger = new A_Log($writer);
		$logger->clear();
		
		$logger->log('one');
		unset($logger);			// destruct should cause write
		
  		$this->assertTrue(file_exists($writer));
		$str = file_get_contents($writer);
#echo "LOG=<pre>$str</pre>";
		$this->assertTrue(preg_match('/[0-9\:\-\ ]* - one\r\n/', $str));
	}

}
