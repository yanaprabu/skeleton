<?php
include_once 'A/Rule/Abstract.php';
/**
 * Rule to check for one request value equaling another
 * 
 * @package A_Validator 
 */

class A_Rule_Match extends A_Rule_Abstract {
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
