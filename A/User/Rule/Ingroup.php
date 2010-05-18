<?php

/**
 * Checks if the group(s) passed to the constructor are group(s) that the user 
 * is a member of. 
 * 
 * $group can be an array or string of comma separated group names
 * if $group is a string, it is split into an array on $this->delimiter
 * special case: if null group (array('')) is passed allow access
 * 
 * @package A_User 
 */
class A_User_Rule_Ingroup {
	protected $groups;
	protected $forward;
	protected $field;
	protected $delimiter;
	
	public function __construct ($groups, $forward=array(), $field='access', $delimiter='|') {
		$this->groups = $groups;
		$this->forward = $forward;
		$this->field = $field;
		$this->delimiter = $delimiter;
	}

	public function setGroups($groups) {
		$this->groups = $groups;
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
	
	public function setDelimiter($delimiter) {
		$this->delimiter = $delimiter;
		return $this;
	}
	
	public function isValid($user) {
		if (is_string($this->groups)) {
			$this->groups = explode ($this->delimiter, $this->groups);
		} else {
			$this->groups = $this->groups;
		}
		// special case: if null group is passed allow access
		if ($this->groups && ($this->groups[0] == '')) {
			return true;
		}
		if ($user && $user->isLoggedIn()) {
	
			if ($this->groups) {
				$usergroups = $user->get($this->field);
				if ($usergroups) {
					// split if not an array
					if (! is_array($usergroups) ) {
						$usergroups = explode ($this->delimiter, $usergroups);
					}
				} else {
					$usergroups = array();
				}
				if (array_intersect ($this->groups, $usergroups) )  {
					return true;
				}
			} else {
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
