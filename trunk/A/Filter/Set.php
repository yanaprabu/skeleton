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
		// if filter is string then we load the class later
		if(is_string($filter)) {
			$filter = func_get_args();
			$fields = null;
		} elseif ($fields && ! is_array($fields)) {
			$fields = array($fields);
		}
		// if for specific fields only then create filter for each field
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
		    $value = $this->chain[$key]->doFilter($container);
		    $name = $this->chain[$key]->getName();
echo "value=$value, name=$name<br/>\n";
			if (is_array($container)) {
				$container[$name] = $value;
			} elseif (is_object($container)) {
				$container->set($name, $value);
			} else {
				$container = $value;
			}
		}
		return $container;
    }
	
}