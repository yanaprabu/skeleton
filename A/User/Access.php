<?php
/**
 * Access.php
 *
 * @package  A_User
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_User_Access
 * 
 * Check if user has access based on supplied rules.
 */
class A_User_Access {
	protected $user;
	protected $rules = array();
	protected $errorMsg = null;
	
	public function __construct($user) {
		$this->user = $user;
	}

	public function addRule($rule) {
		$this->rules[] = $rule;
		return $this;
	}

	/*
	 * errorMsg holds the forward array
	 */
	public function setErrorMsg($forward=array()) {
		$this->errorMsg = $forward;
		return $this;
	}

	public function getErrorMsg($forward=array()) {
		return $this->errorMsg;
	}

	public function run($obj) {
		if (is_a($obj, 'A_Locator')) {
			$request = $obj->get('Request');
		} elseif (is_a($obj, 'A_Http_Request')) {
			$request = $obj;
		} else {
			$request = null;
		}
#echo 'ERRMSG='.print_r($errorMsg, 1).'<br/>';
#dump($errorMsg, 'FORWARD/ERRORMSG: ');
		if ($request) {
			foreach ($this->rules as $rule) {
				if (!$rule->isValid($this->user)) { 
					// A_User_Rule_* use the Rule's errorMsg to hold the forward
					$errorMsg = $rule->getErrorMsg();
					// use global forward
					if (! $errorMsg) {
						$errorMsg = $this->errorMsg;
					}
					#echo 'RULE NOT VALID<br/>';
					if (is_array($errorMsg)) {
						// if array has 3 elements then 1st is module
						if (count($errorMsg) > 2) {
							$request->set('module', array_shift($errorMsg));
						}
						$request->set('controller', array_shift($errorMsg));
						$request->set('action', array_shift($errorMsg));
					} elseif ($errorMsg) {
						$request->set('controller', $errorMsg);
						$request->set('action', '');
					}
				}
			}
		} else {		// a controller - being used as a preFilter
		}
	}
}
