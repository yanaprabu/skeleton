<?php
/**
 * Regexp.php
 *
 * @package  A_Rule
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Rule_Regexp
 * 
 * Rule to check for a value matching a provided regular expression
 */
class A_Rule_Regexp extends A_Rule_Base {
	const ERROR = 'A_Rule_Regexp';

	protected $params = array(
							'regexp' => '', 
							'field' => '', 
							'errorMsg' => '', 
							'optional' => false
							);
	
	public function __construct($regexp, $field, $errorMsg='', $optional=false)
	{
		$this->params['regexp'] = $regexp;
		parent::__construct($field, $errorMsg, $optional);
	}
    protected function validate() {
		return (preg_match($this->params['regexp'], $this->getValue()));
	}
}
