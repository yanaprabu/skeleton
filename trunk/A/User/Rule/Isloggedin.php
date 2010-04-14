<?php
/**
 * Check if user is logged-in 
 * 
 * @package A_User 
 */

class A_User_Rule_Isloggedin {
	protected $forward;
	protected $errorMsg = '';
	
	public function __construct ($forward) {
		$this->forward = $forward;
	}

	public function setForward($forward) {
		$this->forward = $forward;
		return $this;
	}
	
	public function isValid($user) { 
		if ($user && $user->isLoggedIn()) {
			$this->errorMsg = '';
			return true;
		}
		$this->errorMsg = $this->forward;
		return false;
	}

    /**
     * Gets the error message that is to be returned if isValid fails
     * 
     * @return string that contains forward
     */
	public function getErrorMsg() {
		return $this->errorMsg;
	}

}
