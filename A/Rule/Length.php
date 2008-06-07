<?php
include_once 'A/Rule/Abstract.php';

class A_Rule_Length extends A_Rule_Abstract {
	protected $min;
	protected $max;
	
	public function __construct($field, $min, $max, $errorMsg) {
		$this->field= $field;
		$this->min= $min;
		$this->max= $max;
		$this->errorMsg = $errorMsg;
	}
	
	public function isValid($container) {
		$value = strlen($container->get($this->field));
		
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
