<?php
#include_once 'A/Filter/Abstract.php';
/**
 * Filter a string extracting a specfied substring
 * 
 * @package A_Filter 
 */

class A_Filter_Substr extends A_Filter_Abstract {

	protected $start = 0;

	protected $length = 0;
	
	public function __construct($start, $length) {
		$this->start = $start;
		$this->length = $length;
	}
		
	public function filter () {
		$value = $this->getValue();
		if ($this->length < strlen($value)) {
			$value = substr($value, $this->start, $this->length);
		}
		return $value;
	}

}
