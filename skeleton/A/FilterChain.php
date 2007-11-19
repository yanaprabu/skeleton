<?php

class A_FilterChain {
protected $chain = array();
	
	public function addFilter ($filter) {
		if (is_array($filter)) {
			$this->chain = array_merge($this->chain, $filter);
		} else {
			$this->chain[] = $filter;
		}
	}
		
	public function run ($value, $filter=null) {
		if ($filter) {
			$this->chain = $filter;
		}
		foreach ($this->chain as $filter) {
			$value = $filter->run($value);
		}
		return ($value);
	}
	
}

