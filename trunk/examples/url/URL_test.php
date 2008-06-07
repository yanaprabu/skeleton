<?php

include 'URL.php';

class URLTest extends UnitTestCase {
	
	function SetUp() {
	}
	
	function TearDown() {
	}
	
	function testURLSetGet() {
  		$url = new URL();

   		$serverstr = 'http://' . $_SERVER['HTTP_HOST'];

   		$expected = $serverstr . $_SERVER['SCRIPT_NAME'] . $_SERVER['PATH_INFO'];
   		$urlstr = $url->URL();
echo "$urlstr, $expected<br/>";
		$this->assertTrue($urlstr == $expected);

		$targetstr = '/test/test.php';
   		$expected = $serverstr . $targetstr;
   		$urlstr = $url->URL($targetstr);
echo "$urlstr, $expected<br/>";
		$this->assertTrue($urlstr == $expected);
	}
	
}
?>