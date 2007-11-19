<?php

class A_Validator {
	protected $chain = array();
	protected $errorMsg = array();
	
	public function addRule(&$rule) {
		if (is_array($rule)) {
			$this->chain = array_merge($this->chain, $rule);
		} else {
			$this->chain[] = $rule;
		}
	}
		
	public function validate ($container, $rule=null) {
		if ($rule) {
			$this->chain = $rule;
		}
		$this->errorMsg = array();
		foreach ($this->chain as $key => $rule) {
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



