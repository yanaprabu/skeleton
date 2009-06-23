<?php
class AccessCheck {

	protected $user;
	function __construct($user) {
     	$this->user = $user;
	}

	function run($controller) {
     	// now rule objects are only created if denyAccess() exists
     	$this->user->addRule(new A_User_Rule_Issignedin());
     	$this->user->addRule(new A_User_Rule_Ingroup('admin'));
     	// check if access check fails
     	if(! $this->user->isValid()) { 
          	// if access check fails then return DL so Front Controller
          	// will forward to 'signin' instead of requested Action
          	return array('', 'signin', 'index');
     	}
	}
}