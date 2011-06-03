<?php
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
							'field' => '', 
							'array' => array(), 
							'errorMsg' => '', 
							'optional' => false
							);
							
	public function __construct($field, $array, $errorMsg='', $optional=false)
	{
		$this->params['array'] = $array;
		parent::__construct($field, $errorMsg, $optional);
	}

	protected function validate() {
		return in_array($this->getValue(), $this->params['array']);
	}
}
