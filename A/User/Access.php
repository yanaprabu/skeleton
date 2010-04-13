<?php
/**
 * Check if user has access based on supplied rules 
 * 
 * @package A_User 
 */

class A_User_Access {
	protected $user;
	protected $rules = array();
	
	public function __construct($user) {
		$this->user = $user;
	}

	public function addRule($rule) {
		$this->rules[] = $rule;
	}

	public function run($locator) {
		foreach ($this->rules as $rule) {
			if (!$rule->isValid($this->user)) { 
echo 'RULE NOT VALID<br/>';
				$request = $locator->get('Request');
				// A_User_Rule_* use the Rule's errorMsg to hold the forward
				$errorMsg = $rule->getErrorMsg();
echo 'ERRMSG='.print_r($errorMsg, 1).'<br/>';
				dump($errorMsg, 'FORWARD/ERRORMSG: ');
				if ($request && isset($errorMsg)) {
					if (is_string($errorMsg)) {
						$request->set('controller', $errorMsg);
						$request->set('action', '');
					} elseif (is_array($errorMsg)) {
						// if array has 3 elements then 1st is module
						if (count($errorMsg) > 2) {
							$request->set('module', array_shift($errorMsg));
						}
						$request->set('controller', array_shift($errorMsg));
						$request->set('action', array_shift($errorMsg));
					}
				}
			}
		}
	}
}
