<?php
require_once('A/Http/Request.php');
require_once('A/Http/Pathinfo.php');

class Http_Pathinfo_Test extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHttpPathinfo_DefaultMap() {
  		$request = new A_Http_Request();
  		$pathinfo = new A_Http_Pathinfo();
 
		$request->data = array();
		$pathinfo->setPath('');
		$pathinfo->run($request);
		$this->assertTrue($request->get('controller') == '');
		$this->assertTrue($request->get('action') == '');
		
		$request->data = array();
		$pathinfo->__construct();
		$pathinfo->setPath('foo');
		$pathinfo->run($request);
		$this->assertTrue($request->get('controller') == 'foo');
		$this->assertTrue($request->get('action') == '');

		$request->data = array();
		$pathinfo->__construct();
		$pathinfo->setPath('foo/bar');
		$pathinfo->run($request);
		$this->assertTrue($request->get('controller') == 'foo');
		$this->assertTrue($request->get('action') == 'bar');

		$request->data = array();
		$pathinfo->__construct();
		$pathinfo->setPath('foo/bar/param1/value1');
		$pathinfo->run($request);
		$this->assertTrue($request->get('controller') == 'foo');
		$this->assertTrue($request->get('action') == 'bar');
		$this->assertTrue($request->get('param1') == 'value1');

		$request->data = array();
		$pathinfo->__construct();
		$pathinfo->setPath('foo/bar/param1');
		$pathinfo->run($request);
		$this->assertTrue($request->get('controller') == 'foo');
		$this->assertTrue($request->get('action') == 'bar');
		$this->assertFalse($request->has('param1'));

		$request->data = array();
		$pathinfo->__construct();
		$pathinfo->setPath('foo/bar/param1/value1/param2/value2');
		$pathinfo->run($request);
		$this->assertTrue($request->get('controller') == 'foo');
		$this->assertTrue($request->get('action') == 'bar');
		$this->assertTrue($request->get('param1') == 'value1');
		$this->assertTrue($request->has('param2'));
		$this->assertTrue($request->get('param2') == 'value2');

		$request->data = array();
		$pathinfo->__construct();
		$pathinfo->setPath('foo/bar/param1/value1/param2');
		$pathinfo->run($request);
		$this->assertTrue($request->get('controller') == 'foo');
		$this->assertTrue($request->get('action') == 'bar');
		$this->assertTrue($request->has('param1'));
		$this->assertTrue($request->get('param1') == 'value1');
		$this->assertFalse($request->has('param2'));
	}
}
