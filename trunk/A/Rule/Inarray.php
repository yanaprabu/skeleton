<?php
#include_once 'A/Rule/Abstract.php';
/**
 * Inarray.php
 *
 * @package  A_Rule
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Rule_Inarray
 * 
 * Rule to check if string is in provided array
 */
class A_Rule_Inarray extends A_Rule_Base {
	const ERROR = 'A_Rule_Inarray';
	protected $params = array(
							'array' => array(), 
							'field' => '', 
							'errorMsg' => '', 
							'optional' => false
							);

	protected function validate() {
		return in_array($this->getValue(), $this->params['array']);
	}
}
