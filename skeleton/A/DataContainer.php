<?php

class A_DataContainer {
	protected $_data = array();

    public function __construct($source=null) {
		if ($source) {
			$this->_expand($this, $source);
		}
    }

    public function import($source, $node='') {
		if ($source) {
			if ($node) {
				$source = array($node => $source);
			}
			$this->_expand($this, $source);
		}
    }

    protected function _expand($obj, $data) {
		if (isset($data)) {
			foreach ($data as $key => $value) {
				if (is_array($value)) {
			        if (! isset($obj->_data[$key])) {
			        	$obj->_data[$key] = new A_DataContainer;
			        }
					$this->_expand($obj->_data[$key], $value);
				} else {
		        	$obj->_data[$key] = $value;
				}
			}
		}
    }

    public function set($key, $value=null) {
        if ($key) {
	       	$this->_data[$key] = $value;
        }
        return $this;
    }

    public function get($key, $default=null) {
        return isset($this->_data[$key]) ? ($this->_data[$key] instanceof A_DataContainer ? $this->_data[$key]->_data : $this->_data[$key]) : $default;
    }

    public function has($key) {
        return isset($this->_data[$key]);
    }

    protected function __set($key, $value=null) {
        if ($key) {
	       	$this->_data[$key] = $value;
        }
    }

    protected function __get($key) {
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }

}

