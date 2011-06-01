<?php
/**
 * Alnum.php
 *
 * @package  A_Rule
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Rule_Alnum
 * 
 * Rule to enforce a specific value.
 * 
 * @package A_Rule_Set 
 */
class A_Rule_Match extends A_Rule_Base {
	const ERROR = 'A_Rule_Match';
	protected $params = array(
							'refField' => '', 
							'field' => '', 
							'errorMsg' => '', 
							'optional' => false
							);
							
    protected function validate() {
      return (strcmp($this->getValue(), $this->getValue($this->params['refField'])) == 0);
    }
}
