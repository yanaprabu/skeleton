<?php
/**
 * Base filter class -- probably not needed 
 * 
 * @package A_ 
 */

class A_Filter {
	public function run ($value) {
		trigger_error("A_Filter::run not extended.", E_USER_ERROR);
	}
}


/*
class A_Filter_Regexp extends A_Filter {
	protected $from = '';
	protected $to = '';
	
	public function __construct ($from, $to='') {
		$this->from = $from;
		$this->to = $to;
	}
		
	protected function _filter_fix_from ($from) {
		if($from) {
			if(substr($from, 0, 1) != '/') {
				$from = '/' . $from;
			}
			if(substr($from, -1) != '/') {
				$from .= '/';
			}
		}
		return $from;
	}
	
	public function run ($value) {
		if (is_array($this->from)) {
			foreach ($this->from as $key => $val) {
				$this->from[$key] = $this->_filter_fix_from($val);
			}
		} else {
			$this->from = $this->_filter_fix_from($this->from);
		}
		return preg_replace($this->from, $this->to, $value);
	}

}


class A_Filter_Length extends A_Filter {
	protected $length = 0;
	
	public function __construct($length) {
		$this->length = $length;
	}
		
	public function run ($value) {
		if ($this->length < strlen($value)) {
			$value = substr($value, 0, $this->length);
		}
		return $value;
	}

}


class A_Filter_ToUpper extends A_Filter {

public function run ($value) {
	return strtoupper($value);
}

}


class A_Filter_ToLower extends A_Filter {

public function run ($value) {
	return strtolower($value);
}

}
*/