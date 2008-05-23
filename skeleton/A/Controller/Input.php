<?php
include_once 'A/Controller/Action.php';
include_once 'A/FilterChain.php';
include_once 'A/Validator.php';
include_once 'A/DL.php';

class A_Controller_Input extends A_Controller_Action {
	public $params = array();
	protected $handlers = array();
	protected $filters = array();	// global filters run on every parameter
	protected $rules = array();	// global rules run on every parameter
	protected $filterchain;
	protected $validator;
	protected $error = false;
	
	public function __construct($locator=null){
	    parent::__construct($locator);
	}
	 
	public function addHandler($object) {
		if ($object) {
			$this->handlers[] = $object;
		}
	}
	
	public function addFilter($filter, $names=array()) {
		$n = count($this->filters);
		$this->filters[$n]['filter'] = $filter;
		$this->filters[$n]['names'] = $names;
	}
	
	public function addRule($rule, $names=array()) {
		$n = count($this->rules);
		$this->rules[$n]['rule'] = $rule;
		$this->rules[$n]['names'] = $names;
	}
	
	public function addParameter($object) {
		if ($object) {
			$this->params[$object->name] = $object;
		}
	}
	
	public function getParameter($name) {
		if (isset($this->params[$name])) {
			return $this->params[$name];
		}
	}
	
	public function processRequest($request) {
		$filterchain = new A_FilterChain();
		$validator = new A_Validator();
		$this->error = false;
		$param_names = array_keys($this->params);
		if ($param_names) {
			if ($this->filters) {
				foreach ($this->filters as $filter) {
					// if filter is only for specific params do only those, otherwise all
					$names = $filter['names'] ? $filter['names'] : $param_names;
					foreach ($names as $name) {
						$request->set($name, $filterchain->run($request->get($name), $filter['filter']));
					}
				}
			}
			foreach ($param_names as $name) {
				if ($this->params[$name]->filters) {
					$request->set($name, $filterchain->run($request->get($name), $this->params[$name]->filters));
				}
			}
			foreach ($param_names as $name) {
				$this->params[$name]->value = $request->get($name);
				if (isset($this->params[$name]->rules)) {
					if (! $validator->validate($request, $this->params[$name]->rules)) {
						$this->params[$name]->setError($validator->getErrorMsg());
						$this->error = true;
					}
				}
			}
			if ($this->rules) {
				foreach ($this->rules as $rule) {
					// if rule is only for specific params do only those, otherwise all
					$names = $rule['names'] ? $rule['names'] : $param_names;
					foreach ($names as $name) {
						$rule['rule']->setName($name);		// set all rules to work on this parameter
						if (! $validator->validate($request, $rule['rule'])) {
							$this->params[$name]->setError($validator->getErrorMsg());
							$this->error = true;
						}
					}
				}
			}
		}
	
		return ! $this->error;
	}
	
	public function run($locator) {
		$locator->set('Controller', $this);
	
		foreach (array_keys($this->handlers) as $key) {
			$this->handlers[$key]->run($locator);
		}
	
		return $this->error;
	}

	public function set($name, $value) {
		if ($value !== null) {
			$this->params[$name] = $value;
		} else {
			unset($this->params[$name]);
		}
		return $this;
	}

	public function get($name) {
		if (isset($this->params[$name]->value)) {
			return $this->params[$name]->value;
		}
		return $this;
	}

	public function getParameterVarArray($var) {
		if ($var) {
			$data = array();
			foreach (array_keys($this->params) as $field) {
				$data[$field] = $this->params[$field]->$var;
			}
			return $data;
		}
	}

	public function getErrorMsgs($separator=', ') {
		$data = array();
		foreach (array_keys($this->params) as $field) {
			if ($this->params[$field]->isError()) {
				$data[$field] = $this->params[$field]->getErrorMsg($separator);
			}
		}
		return $data;
	}

	public function getValues() {
		return $this->getParameterVarArray('value');
	}
	
	public function isError() {
		return $this->error;
	}

	public function isValid() {
		return ! $this->error;
	}

}


class A_Controller_InputParameter {
	public $name = '';
	public $value = '';
	public $filters = null;
	public $rules = null;
	public $error_msg = array();
	public $renderer = null;
	public $error = false;
	
	public function __construct($name) {
		$this->name = $name;
	}
	
	public function addFilter($filter) {
		$this->filters[] = $filter;
	}
	
	public function addRule($rule) {
		$this->rules[] = $rule;
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}
	
	public function setRenderer($renderer) {
		$this->renderer = $renderer;
		return $this;
	}
	
	public function getErrorMsg($separator=', ') {
		if ($separator) {
			if (is_array($this->error_msg)) {
				return implode($separator, $this->error_msg);
			}
		} else {
			return $this->error_msg;
		}
	}
	
	public function setError($value='') {
		$this->error_msg = $value;
		$this->error = true;
		return $this;
	}
	
	public function isError() {
		return $this->error;
	}
	
	public function isValid() {
		return ! $this->error;
	}

	public function render() {
		if (isset($this->type['renderer'])) {
			if (! isset($this->renderer)){
				$this->renderer = $this->type['renderer'];
				unset($this->type['renderer']);
			}
		}
		// string is name of class with underscores in loadable convention
		if (is_string($this->renderer)){
			// load locator if not loaded
			include_once 'A/Locator.php';
			if (A_Locator::loadClass($this->renderer)) {
				// instantiate render passing the array of parameters
				$this->renderer = new $this->renderer();
			}
		}
		if (isset($this->renderer) && method_exists($this->renderer, 'render')) {
			// set name and value in array passed to renderer
			$this->type['name'] = $this->name;
			$this->type['value'] = $this->value;
			return $this->renderer->render($this->type);
		}
		
		return $this->value;
	}

}
