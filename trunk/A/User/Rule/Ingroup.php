<?php
/**
 * Ingroup.php
 *
 * @package  A_User
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/*
 * $group can be an array or string of comma separated group names
 * if $group is a string, it is split into an array on $this->delimiter
 * special case: if null group (array('')) is passed allow access
 */

/**
 * A_User_Rule_Ingroup
 * 
 * Checks if the group(s) passed to the constructor are group(s) that the user 
 * is a member of.
 */
class A_User_Rule_Ingroup extends A_User_Rule_Base {
	protected $groups;
	protected $delimiter;
	
	public function __construct ($groups, $forward=array(), $field='access', $delimiter='|') {
		$this->forward = $forward;
		$this->field = $field;
		$this->delimiter = $delimiter;
		$this->setGroups($groups);
	}

	public function setGroups($groups) {
		if (is_string($groups)) {
			$this->groups = explode($this->delimiter, $groups);
		} else {
			$this->groups = $groups;
		}
		return $this;
	}
	
	public function setDelimiter($delimiter) {
		$this->delimiter = $delimiter;
		return $this;
	}
	
	/**
	 * TODO: this method needs to set error messages to help debugging
	 */
	public function isValid($user=null) {
		$user = $this->getUser($user);
		$this->errorMsg = array();			// reset each time run
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

}
