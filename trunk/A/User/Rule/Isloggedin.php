<?php
/**
 * Check if user is logged-in 
 * 
 * @package A_User 
 */

class A_User_Rule_Isloggedin extends A_Rule_Abstract {
	
#	public function __construct ($errorMsg) {
#		$this->errorMsg = $errorMsg;
#	}

	public function validate() { 
#		$this->params['errorMsg'] = $this->params['field'];
		$user = $this->container;
dump($user, 'A_User_Rule_Isloggedin');
		if ($user) {
			return $user->isLoggedIn();
		}
		return false;
	}

}
