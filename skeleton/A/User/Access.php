<?php
if (! class_exists('A_Rule')) include 'A/Rule.php';
if (! class_exists('A_Validator')) include 'A/Validator.php';

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
