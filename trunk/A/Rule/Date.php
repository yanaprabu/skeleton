<?php
include_once 'A/Rule/Abstract.php';
/**
 * Rule to check for date value
 * 
 * @package A_Validator 
 */

class A_Rule_Date extends A_Rule_Abstract {
	const ERROR = 'A_Rule_Date';
	protected $params = array(
							'field' => '', 
							'errorMsg' => '', 
							'optional' => false
							);
	
    public function getErrorMsg() {
      return $this->errorMsg;
    }

    protected function validate() {
      return (preg_match("/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$/", $this->getValue(),
              $matches) && checkdate($matches[2], $matches[3], $matches[1]));
    }
}
