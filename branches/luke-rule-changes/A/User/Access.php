<?php
include_once 'A/Rule.php';
include_once 'A/Validator.php';
/**
 * Check if user has access based on supplied rules 
 * 
 * @package A_User 
 */

class A_User_Access extends A_Validator {
	protected $user;
	
	public function __construct($user) {
		$this->user = $user;
	}

	public function run($locator) {
		$this->validate($this->user);
		if ($this->isError()) {
			$request = $locator->get('Request');
			$request->set('action', $this->errorMsg[0]);	// get first error as action
		}
	}
}


/*
 * Check if user's access level is >= required access level
 */
class A_User_Rule_Issignedin extends A_Rule {
	
	public function __construct ($errorMsg) {
		$this->errorMsg = $errorMsg;
	}
	
	public function isValid($user) {
		return $user->isSignedIn();
	}

}
