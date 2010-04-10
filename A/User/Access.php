<?php
/**
 * Check if user has access based on supplied rules 
 * 
 * @package A_User 
 */

class A_User_Access extends A_Rule_Set {
	protected $user;
	
	public function __construct($user) {
		$this->user = $user;
	}

	public function run($locator) {
		if (!$this->isValid($this->user)) { 
			$request = $locator->get('Request');
			$request->set('action', $this->errorMsg[0]);	// get first error as action
		}
	}
}
