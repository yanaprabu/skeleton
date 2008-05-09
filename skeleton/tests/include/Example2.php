<?php
class Example2 {
	public $args = null;
	public $one = null;
	public $two = null;
	public $example = 'example';

	function __construct($args=null) {
		$this->args = $args;
	}
	
	function setBoth($arg1, $arg2) {
		$this->one = $arg1;
		$this->two = $arg2;
	}
	
	function setOne($arg) {
		$this->one = $arg;
	}
	
	function setTwo($arg) {
		$this->two = $arg;
	}
	
	function getOne() {
		return $this->one;
	}
	
	function getTwo() {
		return $this->two;
	}
	
}
