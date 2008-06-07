<?php

class A_Filter_Regexp {
protected $from = '';
protected $to = '';
	
	public function __construct($from, $to='') {
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
