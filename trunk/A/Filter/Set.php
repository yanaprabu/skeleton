<?php
/**
 * Contains multiple filters that are all run  
 * 
 * @package A_Filter 
 */

class A_Filter_Set {
	
    protected $chain = array();
    protected $errorMsg = array();
    protected $dir = 'A_Filter_';

	public function addFilter($filter, $fields=array()) {	
		if(is_string($filter)) {
			$filter = func_get_args();
			$fields = null;
		} else {
			if (! is_array($fields)) {
			    $fields = array($fields);
			}
		}
		if ($fields) {
			
			foreach ($fields as $field) {
				$filter->setName($field);
				$this->chain[] = $filter;
				$filter = clone $filter;
			}
		} else {
			$this->chain[] = $filter;
		}
		return $this;
	}
	
	/**
     * Returns array with filtered data
     * 
     * @return filtered array
     */
	public function doFilter($container) {
		$result = array();
		foreach ($this->chain as $key => $filter) {
		    // class names with params are added as arrays
		    if (is_array($filter)) {
				$name = array_shift($filter);
				// can use built-in rules and $this->dir will be used
				if(strstr($name, '_') === false) {
				    $name = $this->dir . ucfirst($name);
				}
				include_once str_replace('_', '/', $name) . '.php';
				$ref = new ReflectionClass($name);
				$filter = $ref->newInstanceArgs($filter);
				$this->chain[$key] = $filter;
				unset($ref);
		    }
			$result[$this->chain[$key]->getName()] = $this->chain[$key]->doFilter($container);
		}
		return $result;
    }
	
}