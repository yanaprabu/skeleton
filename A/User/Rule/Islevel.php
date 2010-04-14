<?php
/**
 * Check if user's access level is >= required access level
 * 
 * @package A_User 
 */

class A_User_Rule_Islevel {
	protected $level;
	protected $field;
	protected $forward;
	protected $errorMsg = '';
	
	public function __construct ($level, $forward=array(), $field='access') {
		$this->level = $level;
		$this->forward = $forward;
		$this->field = $field;
	}

	public function setLevel($level) {
		$this->level = $level;
		return $this;
	}
	
	public function setForward($forward) {
		$this->forward = $forward;
		return $this;
	}
	
	public function setField($field) {
		$this->field = $field;
		return $this;
	}
	
	public function isValid($user) { 
		if ($user && $user->isLoggedIn()) {
			$userlevel = $user->get($this->field);
			if ($userlevel >= $this->level) {
				$this->errorMsg = '';
				return true;
			}
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
