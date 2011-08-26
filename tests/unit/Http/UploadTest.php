<?php

class Http_UploadTest extends UnitTestCase {

	const MIME_1 = 'foo/bar';
	const MIME_2 = 'foo/baz';
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHttp_UploadNotNull() {
  		$Http_Upload = new A_Http_Upload();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}

	public function testAddAllowedMimes()
	{
		$upload = new A_Http_Upload();

		$this->assertTrue($upload->mimeAllowed(self::MIME_1));
		$this->assertFalse($upload->mimeInList(self::MIME_1, $upload->getMimeWhitelist()));
		$this->assertFalse($upload->mimeInList(self::MIME_1, $upload->getMimeBlacklist()));
		
		$upload->addAllowedMimes(array(self::MIME_1));
		$this->assertTrue($upload->mimeAllowed(self::MIME_1));
		$this->assertTrue($upload->mimeInList(self::MIME_1, $upload->getMimeWhitelist()));
		$this->assertFalse($upload->mimeInList(self::MIME_1, $upload->getMimeBlacklist()));
	}

	public function testAddDeniedMimes()
	{
		$upload = new A_Http_Upload();
		
		$this->assertTrue($upload->mimeAllowed(self::MIME_1));
		$this->assertFalse($upload->mimeInList(self::MIME_1, $upload->getMimeWhitelist()));
		$this->assertFalse($upload->mimeInList(self::MIME_1, $upload->getMimeBlacklist()));
		
		$upload->addDeniedMimes(array(self::MIME_1));
		$this->assertFalse($upload->mimeAllowed(self::MIME_1));
		$this->assertFalse($upload->mimeInList(self::MIME_1, $upload->getMimeWhitelist()));
		$this->assertTrue($upload->mimeInList(self::MIME_1, $upload->getMimeBlacklist()));
	}

}
