<?php
include_once 'A/User/Session.php';
include_once 'A/User/Rule/Ingroup.php';

class A_User_Prefilter_Group {
	
	protected $session;
	protected $action = 'login';
	protected $method = '_requireGroups';
	protected $field = '';
	
	function __construct($session) {
		if ($session) {
			$this->session = $session;
		} else {
			$this->session = new A_Session();
		}
	}

	function setAction($action) {
		$this->action = $action;
	}

	function setPreMethod($method) {
		$this->method = $method;
	}

	function setField($field) {
		$this->field = $field;
	}

	function run($controller) {
		if (method_exists($controller, $this->method)) {
			$this->session->start();
			$user = new A_User_Session($this->session);
			$groups = $controller->{$this->method}();
			$access = new A_User_Rule_Ingroup($groups, 'Access Denied.');
			if ($this->field) {
				$access->setField($this->field);		// change default from 'access'
			}
			if (! $access->isValid($user)) {
				$action = action('', $this->action, '');
				return $action;
			}
		}
	}

}