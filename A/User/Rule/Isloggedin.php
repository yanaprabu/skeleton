<?php
/**
 * Check if user is logged-in 
 * 
 * @package A_User 
 */

class A_User_Rule_Isloggedin extends A_User_Rule_Base {
	
	public function __construct ($forward) {
		$this->forward = $forward;
	}

	public function isValid($user=null) { 
		$user = $this->getUser($user);
		if ($user && $user->isLoggedIn()) {
			$this->errorMsg = '';
			return true;
		}
		$this->errorMsg = $this->forward;
		return false;
	}

}
