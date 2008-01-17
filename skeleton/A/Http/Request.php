<?php

class A_Http_Request {
	public $data = array();
	protected $is_post = false;
   
    public function __construct() {
        if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'POST')) {
            $this->data =& $_POST;
    		$this->is_post = true;
        } else {
            $this->data =& $_GET;
        }
        if (isset($_SERVER['PATH_INFO'])) {
        	$this->data['PATH_INFO'] = trim($_SERVER['PATH_INFO'], '/');
        }        
    }

    public function removeSlashes() {
		if (get_magic_quotes_gpc()) { 
	        $input = array(&$_GET, &$_POST, &$_COOKIE, &$_ENV, &$_SERVER); 
        	while (list($k,$v) = each($input)) { 
                foreach ($v as $key => $val) { 
		            if (!is_array($val)) { 
	                    $input[$k][$key] = stripslashes($val); 
	                    continue; 
		            } 
					$input[] =& $input[$k][$key]; 
                } 
		    } 
		    unset($input); 
		} 
    }

	public function setPathInfo($path_info) {
       	$this->data['PATH_INFO'] = trim($path_info, '/');
	}

	public function isPost() {
		return $this->is_post;
    }

	public function isAjax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
	}

   protected function _get(&$data, $name, $filter=null, $to='') {
    	if (isset($data[$name])) {
	    	if ($filter) {
	    		if (is_string($filter)) {
	    			 return preg_replace($filter, $to, $data[$name]);
	    		} elseif (is_object($filter)) {
	    			 return $filter->run($data[$name]);
	    		}
	    	}
			return $data[$name];
    	}
    }

    public function get($name, $filter=null, $to='') {
		return $this->_get($this->data, $name, $filter, $to);
    }

    public function getPost($name, $filter=null, $to='') {
		return $this->_get($_POST, $name, $filter, $to);
    }

    public function getQuery($name, $filter=null, $to='') {
		return $this->_get($_GET, $name, $filter, $to);
    }

    public function getCookie($name, $filter=null, $to='') {
		return $this->_get($_COOKIE, $name, $filter, $to);
    }

    public function export($filter=null, $pattern=null) {
		if ($filter || $pattern) {
			$export = array();
			foreach (array_keys($this->data) as $key) {
				if (preg_match($pattern, $key)) {
					$export[$key] = $this->_get($this->data, $key, $filter);
				}
			}
			return $export;
		} else {
			return $this->data;
		}
    }

    public function set($name, $value) {
    	$this->data[$name] = $value;
    	return $this;
    }

    public function has($name) {
    	return isset($this->data[$name]);
    }

}
