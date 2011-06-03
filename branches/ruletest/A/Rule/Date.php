<?php
/**
 * Date.php
 *
 * @package  A_Rule
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Rule_Date
 * 
 * Rule to make sure date is in format YYYY-M-D
 */
class A_Rule_Date extends A_Rule_Base {
	const ERROR = 'A_Rule_Date';

    protected function validate() {
      return (preg_match("/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$/", $this->getValue(),
              $matches) && checkdate($matches[2], $matches[3], $matches[1]));
    }
}
