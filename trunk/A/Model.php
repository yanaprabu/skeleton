<?php
/**
 * Model.php
 *
 * @package  A
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Model
 *
 * Base class for Models with filtering and validation
 */
class A_Model {
	protected $datasource = null;	// should be an array() of datasources?
	protected $fields = array();
	protected $filters = array();
	protected $rules = array();
	protected $includeRules = array();
	protected $excludeRules = array();
	protected $fieldClass = 'A_Model_Field';
	protected $errorMsg = array();
	protected $error = false;

	public function addField($objects){
        if(is_array($objects)){
            foreach($objects as $object){
                $this->fields[$object->name] = $object;
            }
        } else {
            $this->fields[$objects->name] = $objects;
        }   
    }
	
	public function addFilter($filter, $fields=array()) {
		if ($fields) {
			if (! is_array($fields)) {
				$fields = array($fields);
			}
			// if field names provided then assign filter to multiple fields
			foreach ($fields as $name) {
				if (! isset($this->fields[$name])) {
					// if field does not exist the create it
					$this->fields[$name] = new A_Model_Field($name);
				}
				$this->fields[$name]->addFilter($filter);
			}
		} else {
			// assign as global filter(s)
			if (is_array($filter)) {
				foreach ($filter as $f) {
					$this->filters[] = $f;
				}
			} else {
				$this->filters[] = $filter;
			}
		}
		return $this;
	}
	
	public function addRule($rule, $fields=array()) {
		if ($fields) {
			if (! is_array($fields)) {
				$fields = array($fields);
			}
			// if field names provided then assign rule to multiple fields
			foreach ($fields as $name) {
				if (! isset($this->fields[$name])) {
					// if field does not exist the create it
					$this->fields[$name] = new A_Model_Field($name);
				}
				$this->fields[$name]->addRule($rule);
			}
		} else {
			// assign as global rule(s)
			if (is_array($rule)) {
				foreach ($rule as $r) {
					$this->rules[] = $r;
				}
			} else {
				$this->rules[] = $rule;
			}
		}
		return $this;
	}
	
	public function excludeRules($rules=array()) {
		$this->excludeRules = $rules;
		return $this;
	}
	
	public function includeRules($rules=array()){
		$this->includeRules = $rules;
		return $this;
	}
	
	public function newField($name) {
		if (! isset($this->fields[$name])) {
			$this->fields[$name] = new $this->fieldClass($name);
		}
		return $this->fields[$name];
	}
	
	public function getField($name, $new=true) {
		if (isset($this->fields[$name])) {
			return $this->fields[$name];
		}
	}
	
	function getRules() {
		return $this->rules;
	}
	
	function getFilters() {
		return $this->filters;
	}
	
	function getFields() {
		return $this->fields;
	}
	
	public function getSaveValues() {
		$data = array();
		foreach (array_keys($this->fields) as $field) {
			if ($this->fields[$field]->save) {
				$data[$field] = $this->fields[$field]->value;
			}
		}
		return $data;
	}

	public function isValid($datasource=null) {

		$filterchain = new A_Filter_Set();
		$validator = new A_Rule_Set();
		$validator->excludeRules($this->excludeRules);
		$validator->includeRules($this->includeRules);
		
		$this->error = false;
		$field_names = array_keys($this->fields);
		if ($field_names) {
			if (!$datasource) {
				$datasource = $this->datasource;
			}
			// set values from datasource
			foreach ($field_names as $name) {
				if ($datasource->has($name)) {
					$this->fields[$name]->value = $datasource->get($name);
				}
			}
			// run global filters
			if ($this->filters) {
				foreach ($field_names as $name) {
					$this->fields[$name]->value = $filterchain->doFilter($this->fields[$name]->value, $this->filters);
				}
			}

			// run rules for each field
			foreach ($this->fields as $field) {   	
				if (isset($field->rules)) {
					foreach($field->rules as $rule ){
						// check if set to override rule's optional setting
						if ($field->optional !== null) {
							$rule->setOptional($field->optional);
						} 	
						$validator->addRule($rule);
					}
				}
			}
			
			// check if there are rules and run those as well
			if ($this->rules) {
				foreach ($this->rules as $rule) { 
					$validator->addRule($rule);
				}
			}			
			
			// if the validator is not valid get its errors
			if(!$validator->isValid($datasource)){
				$this->error = true; 
				$errors = $validator->getErrorMsg(); 
				foreach($errors as $fieldname => $errorarray) { 	
					$this->addErrorMsg($fieldname, $errorarray);
				}
			}
		
		}
			
		return ! $this->error;
	}
	
	public function isError() {
		return $this->error;
	}
	
	public function getFieldNames() {
		$data = array();
		foreach (array_keys($this->fields) as $field) {
			$data[$field] = $this->fields[$field]->name;
		}
		return $data;
	}

	public function getSourceNames() {
		$data = array();
		foreach (array_keys($this->fields) as $field) {
			$data[$field] = $this->fields[$field]->source_name ? $this->fields[$field]->source_name : $data[$field] = $this->fields[$field]->name;
		}
		return $data;
	}

	public function set($name, $value, $default=null) {
		if (isset($this->fields[$name])) {
			if ($value !== null) {
				$this->fields[$name]->value = $value;
			} else {
				$this->fields[$name]->value = $default;
#				unset($this->fields[$name]);
			}
		}
		return $this;
	}
	
	public function get($name) {
		if (isset($this->fields[$name]->value)) {
			return $this->fields[$name]->value;
		}
	}

	public function has($name) {
		return isset($this->fields[$name]);
	}

	public function getFieldVarArray($var) {
		if ($var) {
			$data = array();
			foreach (array_keys($this->fields) as $field) {
				$data[$field] = $this->fields[$field]->$var;
			}
			return $data;
		}
	}

	public function getErrorMsg($separator=null) {
		$data = $this->errorMsg;
		foreach (array_keys($this->fields) as $field) {
			if ($this->fields[$field]->isError()) {
				$data[$field] = $this->fields[$field]->getErrorMsg($separator);
			}
		}
		return $separator === null ? $data : implode($separator, $data);
	}

	public function addErrorMsg($name, $errorMsg) {
		if(isset($this->fields[$name])){
			$this->fields[$name]->addErrorMsg($errorMsg);
		} else {
			// initialize so set for concat below
			if (!isset($this->errorMsg[$name])) {
				$this->errorMsg[$name] = '';
			}
			// fields implode arrays so do the same for the global error messages
			$this->errorMsg[$name] .= is_array($errorMsg) ? implode('', $errorMsg) : $errorMsg;
		}
	}

	public function getValues() {
		return $this->getFieldVarArray('value');
	}
	
	public function save() {
		if (isset($this->datasource) && method_exists($this->datasource, 'save')) {
			$this->datasource->save($this->getFieldVarArray('value'));
			// error messages and return value?
		}
	}
	
}


