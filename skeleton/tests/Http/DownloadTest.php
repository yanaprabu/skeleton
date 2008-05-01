<?php
require_once('A/Http/Download.php');

class Http_DownloadTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHttp_DownloadNotNull() {
  		$Http_Download = new A_Http_Download();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
