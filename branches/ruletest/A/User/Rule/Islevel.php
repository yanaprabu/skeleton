<?php
/**
 * Islevel.php
 *
 * @package  A_User
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_User_Rule_Islevel
 * 
 * Check if user's access level is >= required access level.
 */
class A_User_Rule_Islevel extends A_User_Rule_Base {
	protected $level;
	
	public function __construct ($level, $forward=array(), $field='access') {
		$this->level = $level;
		$this->forward = $forward;
		$this->field = $field;
	}

	public function setLevel($level) {
		$this->level = $level;
		return $this;
	}
	
	public function isValid($user=null) { 
		$user = $this->getUser($user);
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

}
