<?php
include_once 'A/FilterChain.php';
include_once 'A/Validator.php';
include_once 'A/Model/Field.php';
/**
 * Base class for Models with filtering and validation 
 * 
 * @package A_Base 
 */

class A_Model {
	protected $relations = array();
	protected $fields = array();
	protected $filters = array();
	protected $rules = array();
	protected $excludeRules = array();
	protected $fieldClass = 'A_Model_Field';
	protected $error = false;
	
	public function addField(A_Model_Field $object) {
		if ($object) {
			$this->fields[$object->name] = $object;
		}
		return $this;
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
	
	public function getSaveValues() {
		$data = array();
		foreach (array_keys($this->fields) as $field) {
			if ($this->fields[$field]->save) {
				$data[$field] = $this->fields[$field]->value;
			}
		}
		return $data;
	}

	public function process($datasource) {

		$filterchain = new A_FilterChain();
		$validator = new A_Validator();
		$validator->exclude($this->excludeRules);
		
		$this->error = false;
		$field_names = array_keys($this->fields);
		if ($field_names) {
			// set values from datasource
			foreach ($field_names as $name) {
				$this->fields[$name]->value = $datasource->get($name);
			}
			// run global filters
			if ($this->filters) {
				foreach ($field_names as $name) {
					$this->fields[$name]->value = $filterchain->run($this->fields[$name]->value, $this->filters);
				}
			}

			// run rules for each field
			foreach ($this->fields as $field) {   	
			//	if (isset($field->rules)) {
					foreach($field->rules as $rule ){ 	
						$validator->addRule($rule);
					}
			//	}
			}
			// if the validator is not valid get its errors
			if(!$validator->validate($datasource)){
				$this->error = true; 
				$errors = $validator->getErrorMsg(); 
				foreach($errors as $fieldname => $errorarray) { 	
					$this->fields[$fieldname]->setError($errorarray);
				}
			}
			
			
			if ($this->rules) {
				foreach ($this->rules as $rule) {
					// if rule is only for specific fields do only those, otherwise all
					$names = $rule['names'] ? $rule['names'] : $field_names;
					foreach ($names as $name) {
						$rule['rule']->setName($name);		// set all rules to work on this field
						if (! $validator->validate($datasource, $rule['rule'])) {
							$this->fields[$name]->setError($validator->getErrorMsg());
							$this->error = true;
						}
					}
				}
			}
		}
			
		return ! $this->error;
	}
	
	public function isError() {
		return $this->error;
	}
	
	public function isValid() {
		return ! $this->error;
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

	public function set($name, $value) {
		if ($name) {
			if ($value !== null) {
				$this->fields[$name]->value = $value;
			} else {
				unset($this->fields[$name]);
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
		$data = array();
		foreach (array_keys($this->fields) as $field) {
			if ($this->fields[$field]->isError()) {
				$data[$field] = $this->fields[$field]->getErrorMsg($separator);
			}
		}
		return $separator === null ? $data : implode($separator, $data);
	}

	public function getValues() {
		return $this->getFieldVarArray('value');
	}
	
}


