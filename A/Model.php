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
	public $fields = array();
	public $filters = array();
	public $rules = array();
	public $excludeRules = array();
	protected $error = false;
	
	public function addField(A_Model_Field $object) {
		if ($object) {
			$this->fields[$object->name] = $object;
		}
		return $this;
	}
	
	public function addFilter($filter) {
		if ($filter) {
			$this->filters[] = $filter;
		}
		return $this;
	}
	
	public function addRule($rule) {
		if ($rule) {
			$this->rules[] = $rule;
		}
		return $this;
	}
	
	public function excludeRules($rules=array()) {
		$this->excludeRules = $rules;
		return $this;
	}
	
	public function getField($name) {
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
			if ($this->filters) {
				foreach ($this->filters as $filter) {
					// if filter is only for specific fields do only those, otherwise all
					$names = $filter['names'] ? $filter['names'] : $field_names;
					foreach ($names as $name) {
						$datasource->set($name, $filterchain->run($datasource->get($name), $filter['filter']));
					}
				}
			}
			foreach ($field_names as $name) {
				if ($this->fields[$name]->filters) {
					$datasource->set($name, $filterchain->run($datasource->get($name), $this->fields[$name]->filters));
				}
			}
			foreach ($field_names as $name) {
				$this->fields[$name]->value = $datasource->get($name);
				if (isset($this->fields[$name]->rules)) {
					if (! $validator->validate($datasource, $this->fields[$name]->rules)) {
						$this->fields[$name]->setError($validator->getErrorMsg());
						$this->error = true;
					}
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


