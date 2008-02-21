<?php

class A_Template {
	protected $template = '';
	protected $data = array();
	protected $filename = '';
	
	public function __construct($filename='', $data=array()) {
	    $this->filename = $filename;
	    if ($data) {
	    	$this->import($data);
	    }
	}
	
	public function setTemplate($template) {
	    $this->template = $template;
		return $this;
	}
	
	public function setFilename($filename) {
	    $this->filename = $filename;
		return $this;
	}
	
	public function clear() {
	    $this->data = array();
		return $this;
	}
	
	public function renderArray($array, $block='') {
	   	$str = '';
	   	foreach ($array as $key1 => $value1) {
	   		if (is_array($value1)) {
		   		foreach ($value1 as $key2 => $value2) {
			   		$this->set($key2, $value2);
			   	}
		   		$str .= $this->render($block);
	   		} else {
			   	$this->set($key1, $value1);
		   	}
	   	}
	   	if (! isset($key2)) {
		   	$str .= $this->render($block);
	   	}
	   	return $str;
	}

    public function get($name) {
    	return (isset($this->data[$name]) ? $this->data[$name] : null);
    }

    public function set($name, $value) {
    	$this->data[$name] = $value;
    	return $this;
    }

    public function import($data) {
    	$this->data = array_merge($this->data, $data);
		return $this;
    }

    public function has($name) {
    	return isset($this->data[$name]);
    }

	public function __toString() {
		return $this->render();
	}

}
