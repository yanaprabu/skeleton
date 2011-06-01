<?php
#include_once 'A/Rule/Abstract.php';
/**
 * Digit.php
 *
 * @package  A_Rule
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Rule_Digit
 * 
 * Rule to check for values with only digits
 */
class A_Rule_Digit extends A_Rule_Base {
	const ERROR = 'A_Rule_Digit';

   protected function validate() {
      return (preg_match("/^[[:digit:]]+$/", $this->getValue()));
    }
}
