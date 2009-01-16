<?php
include_once 'A/Filter/Abstract.php';
/**
 * Filter a string with the trim() function
 * 
 * @package A_Filter 
 */

class A_Filter_Trim extends A_Filter_Abstract {
	protected $charset = null;

	public function __construct($charset=null) {
		$this->charset = $charset;
	}

	public function filter () {
		return trim($this->getValue(), $this->charset);
	}

}