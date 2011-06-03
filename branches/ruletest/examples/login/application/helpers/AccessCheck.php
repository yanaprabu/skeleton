<?php
class AccessCheck {

	protected $user;
	function __construct($user) {
     	$this->user = $user;
	}

	function run($controller) {
     	// now rule objects are only created if denyAccess() exists
     	$isloggedin = new A_User_Rule_Isloggedin();
     	$ingroup = new A_User_Rule_Ingroup('admin');
     	// check if access check fails
     	if(! $isloggedin->isValid($this->user) && ! $ingroup->isValid($this->user)) { 
          	// if access check fails then return DL so Front Controller
          	// will forward to 'login' instead of requested Action
          	return array('', 'login', 'index');
     	}
	}
}