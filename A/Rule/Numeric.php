<?php
#include_once 'A/Rule/Abstract.php';
/**
 * Numeric.php
 *
 * @package  A_Rule
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Rule_Numeric
 * 
 * Rule to check for a value being a number
 */
class A_Rule_Numeric extends A_Rule_Base {
	const ERROR = 'A_Rule_Numeric';
	
    protected function validate() {
      return (is_numeric($this->getValue()));
    }
}
