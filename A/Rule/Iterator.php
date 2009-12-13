<?php
#include_once 'A/Rule/Abstract.php';
/**
 * Rule to check a rule against an array of values 
 * 
 * @package A_Rule_Set 
 */

class A_Rule_Iterator extends A_Rule_Abstract {
	protected $rule;
	protected $params = array(
							'rule' => null, 
							'field' => '', 
							'errorMsg' => '', 
							'optional' => false
							);

    public function get($name) {
      return $this->value;
    }

    protected function validate() {
		$data = $this->getValue();
		$result = false;
		if (is_array($data)) {
			foreach ($data as $value) {
				$this->value = $value;		// to allow access to $this->get() above
				if (! $this->params['rule']->isValid($this)) {
					return false;
				}
			}
			return true;
		} else {
			return ($this->params['rule']->validate($this->getValue()));
		}
    }
}
