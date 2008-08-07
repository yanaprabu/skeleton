<?php
include_once 'A/Rule/Abstract.php';
/**
 * Rule to check for strings of a specified length
 * 
 * @package A_Validator 
 */

class A_Rule_Length extends A_Rule_Abstract {
	const ERROR = 'A_Rule_Length';
	protected $min;
	protected $max;
	
	public function __construct($field, $min, $max, $errorMsg) {
		$this->field= $field;
		$this->min= $min;
		$this->max= $max;
		$this->errorMsg = $errorMsg;
	}
	
	protected function validate() {
		$value = strlen($this->getValue());
		
		// Only maximum defined
		if ($this->min == NULL) {
			return ($value <= $this->max);
		}
		// Only minimum defined
		if ($this->max == NULL) {
			return ($value >= $this->min);
		}
		// Range defined
		return ($this->min <= $value && $value <= $this->max);
	}
}
