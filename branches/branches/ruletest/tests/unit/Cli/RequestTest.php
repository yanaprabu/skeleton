<?php

class Cli_RequestTest extends UnitTestCase {
	
	function setUp() {
 		// fake CLI args
 		$_SERVER['argv'] = array(0=>$_SERVER['SCRIPT_FILENAME'], 1=>'module/controller/action', 2=>'foo', 3=>'bar', 4=>'foo=boo', 5=>'far=bar', 6=>'x=xxx&y=yyy');
 		$_SERVER['argc'] = count($_SERVER['argv']);
	}
	
	function TearDown() {
	}
	
	function testCli_RequestArgs() {
 		$request = new A_Cli_Request();
  		
		$this->assertEqual($request->get(2), 'foo');
		$this->assertEqual($request->get(3), 'bar');
		$this->assertEqual($request->get('PATH_INFO'), 'module/controller/action');
		$this->assertFalse($request->has('baz'));
	}
	
	function testCli_RequestHas() {
  		$request = new A_Cli_Request();
		$request->data = array('foo'=>'boo', 'bar'=>'far',);
		
		$this->assertTrue($request->has('foo'));
		$this->assertTrue($request->has('bar'));
		$this->assertFalse($request->has('baz'));
	}
	
	function testCli_RequestGet() {
  		$request = new A_Cli_Request();
		$request->data = array('foo'=>'boo', 'bar'=>'far',);
		
		$this->assertEqual($request->get('foo'), 'boo');
		$this->assertEqual($request->get('bar'), 'far');
		$this->assertEqual($request->get('baz'), '');
	}
	
	function testCli_RequestGetFilter() {
  		$request = new A_Cli_Request();
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
	
	function testCli_RequestGetFilterArray() {
  		$request = new A_Cli_Request();
		$request->data = array('foo'=>'boo', 'bar'=>'far',);
  		
		// two filters
		$this->assertEqual($request->get('bar', array('/[^a-f]/', 'strtoupper')), 'FA');
	}
	
	function testCli_RequestGetParamArray() {
  		$request = new A_Cli_Request();
		$request->data = array('foo'=>array('faz'=>'baz', 'bar'=>'far',));
  		
		// two filters
		$this->assertEqual($request->get('foo', 'strtoupper'), array('faz'=>'BAZ', 'bar'=>'FAR',));
	}
	
	function testCli_RequestGetWithGlobalFilter() {
  		$request = new A_Cli_Request();
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
	
}
