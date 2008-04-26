<?php

class A_Validator {
	protected $chain = array();
	protected $errorMsg = array();
	protected $dir = 'A_Rule_';
	
	public function addRule($rule) {
		if (is_string($rule)) {
			$rule = func_get_args();
		}
		$this->chain[] = $rule;
	}
		
	public function validate ($container, $rule=null) {
		if ($rule) {
			$this->chain = $rule;
		}
		$this->errorMsg = array();
		foreach ($this->chain as $key => $rule) {
			// class names with params are added as arrays
			if (is_array($rule)) {
				$name = array_shift($rule);
				// can use built-in rules and $this->dir will be used
				if(strstr($name, '_') === false) {
					$name = $this->dir . ucfirst($name);
				}
				include_once str_replace('_', '/', $name) . '.php';
				$ref = new ReflectionClass($name);
				$rule = $ref->newInstanceArgs($rule);
				$this->chain[$key] = $rule;
				unset($ref);
			}
			if (! $this->chain[$key]->isValid($container)) {
				$this->errorMsg[] = $this->chain[$key]->getErrorMsg();
			}
		}
		return $this->isValid();
	}
	
	public function isError() {
		return ! empty($this->errorMsg);
	}
	
	public function isValid() {
		return empty($this->errorMsg);
	}
	
	public function getErrorMsg() {
		return $this->errorMsg;
	}

}



