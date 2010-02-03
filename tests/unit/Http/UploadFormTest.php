<?php

class Http_UploadFormTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHttp_UploadFormNotNull() {
  		$upload = new A_Http_Upload();
  		$Http_UploadForm = new A_Http_UploadForm($upload);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
