<?php
/**
 * Check if user is logged-in 
 * 
 * @package A_User 
 */

class A_User_Rule_Isloggedin extends A_Rule_Abstract {
	
	public function __construct ($errorMsg) {
		$this->errorMsg = $errorMsg;
	}
	
	public function isValid() {
		$user = $this->getValue();
		if ($user) {
			return $user->isLoggedIn();
		}
		return false;
	}

}
