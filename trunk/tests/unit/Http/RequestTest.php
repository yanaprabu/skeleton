<?php

class Http_RequestTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHttp_RequestHas() {
		$request = new A_Http_Request();
		$request->data = array('foo'=>'boo', 'bar'=>'far',);
		
		$this->assertTrue($request->has('foo'));
		$this->assertTrue($request->has('bar'));
		$this->assertFalse($request->has('baz'));
	}
	
	function testHttp_RequestGet() {
		$request = new A_Http_Request();
		$request->data = array('foo'=>'boo', 'bar'=>'far',);
		
		$this->assertEqual($request->get('foo'), 'boo');
		$this->assertEqual($request->get('bar'), 'far');
		$this->assertEqual($request->get('baz'), '');
	}
	
	function testHttp_RequestGetFilter() {
		$request = new A_Http_Request();
		$request->data = array('foo'=>'boo', 'bar'=>'far',);
		
		// regexp
		$this->assertEqual($request->get('foo', '/[^a-z]/'), 'boo');
		$this->assertEqual($request->get('bar', '/[^a-f]/'), 'fa');
		
		// function name
		$this->assertEqual($request->get('bar', 'strtoupper'), 'FAR');

		// filter object
		$toupper = new A_Filter_Toupper();
		$this->assertEqual($request->get('bar', $toupper), 'FAR');

		// call_user_func style
		$this->assertEqual($request->get('bar', array(array($toupper, 'doFilter'))), 'FAR');
	}
	
	function testHttp_RequestGetFilterArray() {
		$request = new A_Http_Request();
		$request->data = array('foo'=>'boo', 'bar'=>'far',);
		
		// two filters
		$this->assertEqual($request->get('bar', array('/[^a-f]/', 'strtoupper')), 'FA');
	}
	
	function testHttp_RequestGetParamArray() {
		$request = new A_Http_Request();
		$request->data = array('foo'=>array('faz'=>'baz', 'bar'=>'far',));
		
		// two filters
		$this->assertEqual($request->get('foo', 'strtoupper'), array('faz'=>'BAZ', 'bar'=>'FAR',));
	}
	
	function testHttp_RequestGetWithGlobalFilter() {
		$request = new A_Http_Request();
		$request->data = array('foo'=>'boo', 'bar'=>'far',);
		
		// regexp
		$this->assertEqual($request->setFilters('/[^a-f]/')->get('bar'), 'fa');
		
		// function name
		$this->assertEqual($request->setFilters('strtoupper')->get('bar'), 'FAR');

		// filter object
		$toupper = new A_Filter_Toupper();
		$this->assertEqual($request->setFilters($toupper)->get('bar', $toupper), 'FAR');

		// call_user_func style
		$this->assertEqual($request->setFilters(array('/[^a-f]/', $toupper))->get('bar'), 'FA');
	}
	
	function testHttp_RequestGetOverPost() {
		foreach ($_GET as $key => $val) {
			unset($_GET[$key]);
		}
		foreach ($_POST as $key => $val) {
			unset($_POST[$key]);
		}
		$_POST['foo'] = 'one';
		$_GET['bar'] = 'two';

		// try with POST
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$request = new A_Http_Request();
		
		// default don't allow GET over POST
		$this->assertEqual($request->get('foo'), 'one');
		$this->assertEqual($request->get('bar'), '');
		$request->allowGetOverPost();
		$this->assertEqual($request->get('bar'), 'two');

		// try wit GET so we did not break anything
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$request = new A_Http_Request();
		
		// default don't allow GET over POST
		$this->assertEqual($request->get('foo'), '');
		$this->assertEqual($request->get('bar'), 'two');
		$request->allowGetOverPost();
		$this->assertEqual($request->get('bar'), 'two');
	}
	
}
