<?php
/**
 * Base class for A_User_Rule_ classes
 * 
 * @package A_User 
 */

abstract class A_User_Rule_Base {
	protected $field;
	protected $forward;
	protected $user;
	protected $errorMsg = '';
	
	public function setForward($forward) {
		$this->forward = $forward;
		return $this;
	}
	
	public function setField($field) {
		$this->field = $field;
		return $this;
	}
	
	public function setUser($user) {
		$this->user = $user;
		return $this;
	}
	
	public function getUser($user=null) {
		return isset($user) ? $user : $this->user;
	}
	
	abstract public function isValid($user=null);
	
	/**
	 * Gets the error message that is to be returned if isValid fails
	 * 
	 * @return string that contains forward
	 */
	public function getErrorMsg() {
		return $this->errorMsg;
	}

}
