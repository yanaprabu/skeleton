<?php

class A_Filter_Length {
protected $length = 0;
	
	public function __construct($length) {
		$this->length = $length;
	}
		
	public function run ($value) {
		if ($this->length < strlen($value)) {
			$value = substr($value, 0, $this->length);
		}
		return $value;
	}

}
