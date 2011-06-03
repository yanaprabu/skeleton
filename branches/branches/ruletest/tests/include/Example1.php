<?php
class Example1 {
	public $args = null;
	public $one = null;
	public $two = null;
	public $example = 'example';

	function __construct($args=null) {
		$this->args = $args;
	}
	
	function one($arg) {
		$this->one = $arg;
		return $arg;
	}
	
	function two($arg) {
		$this->two = $arg;
		return $arg;
	}
	
}
?>