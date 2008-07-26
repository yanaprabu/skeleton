<?php
include_once 'A/Rule/Abstract.php';
/**
 * Rule to check for a value being in a numeric range
 * 
 * @package A_Validator 
 */

class A_Rule_Range extends A_Rule_Abstract {
	const ERROR = 'A_Rule_Range';
	protected $min;
	protected $max;

    public function __construct($field, $min, $max, $errorMsg) {
		$this->field    = $field;
		$this->min      = $min;
		$this->max      = $max;
		$this->errorMsg = $errorMsg;
    }

    public function isValid($container) {
		$value = $container->get($this->field);

		// Only maximum defined
		if ($this->min == NULL) {
			return ($value <= $this->max);
		}
		// Only minimum defined
		if ($this->max == NULL) {
			return ($value >= $this->min);
		}
		// Range defined
		return (($this->min <= $value) && ($value <= $this->max));
	}
}
