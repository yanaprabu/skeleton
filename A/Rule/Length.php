<?php
#include_once 'A/Rule/Abstract.php';
/**
 * Rule to check for strings of a specified length
 * 
 * @package A_Rule_Set 
 */

class A_Rule_Length extends A_Rule_Abstract {
	const ERROR = 'A_Rule_Length';
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
		$this->min = $min;
		$this->max = $max;
		$this->errorMsg = $errorMsg;
	}
*/

	protected function validate() {
		$length = strlen($this->getValue());
		
		// Only maximum defined
		if ($this->params['min'] == null) {
			return ($length <= $this->params['max']);
		}
		// Only minimum defined
		if ($this->params['max'] == null) {
			return ($length >= $this->params['min']);
		}
		// Range defined
		return ($this->params['min'] <= $length && $length <= $this->params['max']);
	}
}
