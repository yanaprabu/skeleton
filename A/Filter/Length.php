<?php
#include_once 'A/Filter/Abstract.php';
/**
 * Filter string using specified length
 * 
 * @package A_Filter 
 */

class A_Filter_Length extends A_Filter_Abstract {
	protected $length = 0;
	
	public function __construct($length) {
		$this->length = $length;
	}
		
	public function filter() {
		if ($this->length < strlen($this->getValue())) {
			$value = substr($this->getValue(), 0, $this->length);
		}
		return $value;
	}

}
