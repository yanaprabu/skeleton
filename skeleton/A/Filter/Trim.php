<?php

class A_Filter_Trim {
protected $charset = null;

	public function __construct($charset) {
		$this->charset = $charset;
	}

	public function run ($value) {
		return trim($value, $this->charset);
	}

}
