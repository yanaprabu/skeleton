<?php
#include_once 'A/Filter/Abstract.php';
/**
 * Filter a string with a regular expression
 * 
 * @package A_Filter 
 */

class A_Filter_Regexp extends A_Filter_Abstract {

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
	
	public function filter () {
		if (is_array($this->from)) {
			foreach ($this->from as $key => $val) {
				$this->from[$key] = $this->_filter_fix_from($val);
			}
		} else {
			$this->from = $this->_filter_fix_from($this->from);
		}
		return preg_replace($this->from, $this->to, $this->getValue());
	}

}
