<?php
if (! class_exists('A_Http_View')) include 'A/Http/View.php';

class A_Http_Response extends A_Http_View {

    public function __construct($name='') {
    	parent::__construct($name);
    }
    
    public function render() {
        if ($this->headers) {
        	foreach ($this->cookies as $name => $values) {
            	header("$name: " . implode(',', $values));
        	}
        }
        if ($this->cookies) {
        	foreach ($this->cookies as $args) {
        		call_user_func_array('setcookie', $args);
        	}
        }
        if ($this->redirect) {
        	$base = $_SERVER['SERVER_NAME'] . dirname($_SERVER['SCRIPT_NAME']);
            if (! preg_match('/^http?\:\/\//i', $this->redirect) && (strpos($this->redirect, $base) === false)) {
				if (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == 'on')) {
					$protocol = 'https://';
				} else {
					$protocol = 'http://';
				}
	            if (substr($base, -1, 1) != '/') {
	            	$base .= '/';
	            }
	            $this->redirect = $protocol . $base . preg_replace('/^[\/\.]*/', '', $this->redirect);
            }
			header('Location: ' . $this->redirect);
        } else {
        	parent::render();
	        foreach ($this->headers as $field => $params) {
	            if (! is_null($params)) {
	                header($field . ': ' . implode(', ', $params));
	            }
	        }
	        return $this->content;
        }
    }
    	
    public function out() {
    	echo $this->render();
    }
    
}
