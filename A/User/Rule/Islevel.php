<?php
include_once 'A/Rule/Abstract.php';

/*
 * Check if user's access level is >= required access level
 */
class A_User_Rule_Islevel extends A_Rule_Abstract {
	protected $level;
	
	// parameter that is usually errormsg is the action
	public function __construct ($level, $errorMsg) {
		$this->level = $level;
		$this->errorMsg = $errorMsg;
	}
	
	public function setLevel($level) {
		$this->level = $level;
		return $this;
	}
	
	public function validate() {
		$allow = false;
		if ($this->container->isSignedIn()) {
			$userlevel = $this->container->get($this->field);
			if ($userlevel >= $this->level) {
				$allow = true;
			}
		}
		return $allow;
	}

}
