<?php
/**
 * Hasrole.php
 *
 * @package  A_User
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/*
 * 
 * $group can be an array or string of comma separated group names
 * if $group is a string, it is split into an array on $this->delimiter
 * special case: if null group (array('')) is passed allow access
 */

/**
 * A_User_Rule_Hasrole
 * 
 * Checks if the group(s) passed to the constructor are group(s) that the user is a member of.
 */
class A_User_Rule_Hasrole extends A_Rule_Set {
	protected $params = array(
							'errorMsg' => '', 
							'optional' => false
							);
	
	public function allow() {
		return $this;
	}
	
	public function deny() {
		return $this;
	}
	
	public function validate() {
	}

}
