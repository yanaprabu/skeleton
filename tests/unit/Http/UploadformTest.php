<?php

class Http_UploadformTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHttp_UploadformNotNull() {
  		$upload = new A_Http_Upload();
  		$Http_Uploadform = new A_Http_Uploadform($upload);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
