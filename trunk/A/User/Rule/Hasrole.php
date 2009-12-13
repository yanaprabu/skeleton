<?php
#include_once 'A/Rule/Set.php';

/*
 * Checks if the group(s) passed to the constructor are group(s) that the user 
 * is a member of. 
 * $group can be an array or string of comma separated group names
 * if $group is a string, it is split into an array on $this->delimiter
 * special case: if null group (array('')) is passed allow access
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
