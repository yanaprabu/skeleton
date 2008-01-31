<?php

class articlesModel {
	public $foo = 'default';
	public $bar = 'example';
	
	function foo() {
		return 'foo';
	}

	function bar() {
		return 'bar';
	}
	function listAll(){
		return array('one article','two article');
	}
}