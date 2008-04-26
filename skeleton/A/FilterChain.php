<?php

class A_FilterChain {
	protected $chain = array();
	protected $dir = 'A_Filter_';
	
	public function addFilter ($filter) {
		if (is_string($filter)) {
			$filter = func_get_args();
		}
		$this->chain[] = $filter;
	}
		
	public function setDir($dir) {
		$this->dir = strreplace('/', '_', rtrim($dir, '_')) . '_';
	}
		
	public function run($value, $filter=null) {
		if ($filter) {
			$this->chain = $filter;
		}
		foreach ($this->chain as $key => $filter) {
			// non-objects are added as arrays
			if (is_array($filter)) {
				$name = array_shift($filter);
				if (function_exists($name)) {
					$value = call_user_func_array($name, $value);
					continue;
				} else {
					// can use built-in filters and $this->dir will be used
					if(strstr($name, '_') === false) {
						$name = $this->dir . ucfirst($name);
					}
					include_once str_replace('_', '/', $name) . '.php';
					$ref = new ReflectionClass($name);
					$filter = $ref->newInstanceArgs($filter);
					$this->chain[$key] = $filter;
					unset($ref);
				}
			}
			$value = $filter->run($value);
		}
		return $value;
	}
	
}

