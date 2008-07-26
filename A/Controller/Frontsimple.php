<?php
/**
 * Simple front controller implementation
 * 
 * @package A_Controller
 */

class A_Controller_Frontsimple{
protected $action_dir;
protected $default_action;
protected $error_action;
protected $action_param;
protected $action = '';
	 
	public function __construct($action_dir='controllers', $default_action='home', $error_action='error', $action_param='action'){
	    $this->action_dir = rtrim($action_dir, '/') . '/';
	    $this->default_action = $default_action;
	    $this->error_action = $error_action;
	    $this->action_param = $action_param;
	}
	 
	public function requestMapper($action_param, $default_action) {
	    if (isset($_GET[$action_param])) {
	         $action = preg_replace('/[^a-zZ-Z0-9\_\-]/', '', $_GET[$action_param]);
	    } else {
	         $action = $default_action;
	    }
	    return $action;
	}
	 
	public function commandFactory($action_dir, $action){
	    $obj = null;
	    $filename = $action_dir . $action . '.php';
	    if (file_exists($filename)) {
	        include($filename);
	        if (class_exists($action)) {
	              $obj = new $action();
	        }
	    }
	    return $obj;
	}
	 
	public function run(){
		$this->action = $this->requestMapper($this->action_param, $this->default_action);
	    $obj = $this->commandFactory($this->action_dir, $this->action);
	    if (! $obj) {
	        $obj = $this->commandFactory($this->error_action, '');
	    }
	    $obj->run();
	}
 
}
