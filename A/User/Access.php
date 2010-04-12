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
echo 'RULE: <pre>' . print_r($rule, 1) . '</pre>';
			if (!$rule->isValid($this->user)) { 
				$request = $locator->get('Request');
				// A_User_Rule_* use the Rule's errorMsg to hold the forward
				$errorMsg = $rule->getErrorMsg();
dump($errorMsg, 'FORWARD/ERRORMSG: ');
				if ($request && isset($errorMsg[0])) {
					$request->set('controller', $errorMsg[0]);
				}
			}
		}
	}
}
