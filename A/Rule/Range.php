<?php
/**
 * Range.php
 *
 * @package  A_Rule
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Rule_Range
 * 
 * Rule to check for a value being within a numeric range
 * 
 * @package A_Rule_Set 
 */
class A_Rule_Range extends A_Rule_Base {
	const ERROR = 'A_Rule_Range';
	protected $params = array(
							'min' => null, 
							'max' => null, 
							'field' => '', 
							'errorMsg' => '', 
							'optional' => false
							);
	
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
