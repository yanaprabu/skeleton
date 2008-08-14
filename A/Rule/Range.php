<?php
include_once 'A/Rule/Abstract.php';
/**
 * Rule to check for a value being in a numeric range
 * 
 * @package A_Validator 
 */

class A_Rule_Range extends A_Rule_Abstract {
	const ERROR = 'A_Rule_Range';
#	protected $min;
#	protected $max;
	protected $params = array(
							'min' => null, 
							'max' => null, 
							'field' => '', 
							'errorMsg' => '', 
							'optional' => false
							);
	
/*
	public function __construct($field, $min, $max, $errorMsg) {
		$this->field= $field;
		$this->min= $min;
		$this->max= $max;
		$this->errorMsg = $errorMsg;
	}
*/
	
    protected function validate() {
		$value = $this->getValue();

		// Only maximum defined
		if ($this->params['min'] == NULL) {
			return ($value <= $this->params['max']);
		}
		// Only minimum defined
		if ($this->params['max'] == NULL) {
			return ($value >= $this->params['min']);
		}
		// Range defined
		return (($this->params['min'] <= $value) && ($value <= $this->params['max']));
	}
}
