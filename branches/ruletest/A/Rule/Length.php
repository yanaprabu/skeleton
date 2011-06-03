<?php
/**
 * Length.php
 *
 * @package  A_Rule
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Rule_Length
 * 
 * Rule to make sure a string's length is within the range specified.  To create an open-ended range, set min/max as null.
 */
class A_Rule_Length extends A_Rule_Base {
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
