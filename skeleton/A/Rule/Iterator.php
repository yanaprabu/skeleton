<?php
if (! class_exists('A_Rule_Abstract')) include 'A/Rule/Abstract.php';

class A_Rule_Iterator extends A_Rule_Abstract {
protected $rule;
protected $value;
	
    public function __construct($field, $rule, $errMsg) {
		$this->field    = $field;
		$this->rule     = $rule;
		$this->max      = $max;
		$this->errorMsg = $errorMsg;
    }

    public function get($name) {
      return $this->value;
    }

    public function isValid($container) {
		$data = $container->get($this->field);
		$result = false;
		if (is_array($data)) {
			foreach ($data as $value) {
				$this->value = $value;		// for $this->get()
				if (! $this->rule->isValid($this)) {
					return false;
				}
			}
			return true;
		} else {
			return ($this->rule->isValid($container));
		}
    }
}
