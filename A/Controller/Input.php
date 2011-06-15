<?php
/**
 * Input.php
 *
 * @package  A_Controller
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Controller_Input
 * 
 * Controller class with request filtering and validation
 */
class A_Controller_Input extends A_Controller_Action
{

	public $params = array();
	protected $filters = array();	// global filters run on every parameter
	protected $rules = array();		// global rules run on every parameter
	protected $filterchain;
	protected $validator;
	protected $error = false;
	
	public function __construct($locator=null)
	{
	    parent::__construct($locator);
	}
	
	public function addFilter($filter, $names=array())
	{
		$n = count($this->filters);
		$this->filters[$n]['filter'] = $filter;
		$this->filters[$n]['names'] = $names;
	}
	
	public function addRule($rule, $names=array())
	{
		$n = count($this->rules);
		$this->rules[$n]['rule'] = $rule;
		$this->rules[$n]['names'] = $names;
	}
	
	public function addField($object)
	{
		if ($object) {
			$this->params[$object->name] = $object;
		}
	}
	
	public function getField($name)
	{
		if (isset($this->params[$name])) {
			return $this->params[$name];
		}
	}
	
	public function processRequest($request)
	{
		$filterchain = new A_Filter_Set();
		$validator = new A_Rule_Set();
		$this->error = false;
		$param_names = array_keys($this->params);
		if ($param_names) {
			if ($this->filters) {
				foreach ($this->filters as $filter) {
					// if filter is only for specific params do only those, otherwise all
					$names = $filter['names'] ? $filter['names'] : $param_names;
					foreach ($names as $name) {
						$request->set($name, $filterchain->doFilter($request->get($name), $filter['filter']));
					}
				}
			}
			foreach ($param_names as $name) {
				if ($this->params[$name]->filters) {
					$request->set($name, $filterchain->doFilter($request->get($name), $this->params[$name]->filters));
				}
			}
			foreach ($param_names as $name) {
				$this->params[$name]->value = $request->get($name);
				if (isset($this->params[$name]->rules)) {
					$validator->clearRules();
					$validator->addRule($this->params[$name]->rules);
					if (! $validator->isValid($request)) {
						$errorMsgs = $validator->getErrorMsg();
						if (isset($errorMsgs[$name])) {
							$this->params[$name]->setError($errorMsgs[$name]);
						}
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
		
		return !$this->error;
	}
	
	public function set($name, $value, $default=null)
	{
		if (isset($this->params[$name])) {
			if ($value !== null) {
				$this->params[$name]->value = $value;
			} elseif ($default !== null) {
				$this->params[$name] = $default;
			} else {
				unset($this->params[$name]);
			}
		}
		return $this;
	}
	
	public function get($name)
	{
		if (isset($this->params[$name]->value)) {
			return $this->params[$name]->value;
		}
		return $this;
	}
	
	public function getFieldVarArray($var)
	{
		if ($var) {
			$data = array();
			foreach (array_keys($this->params) as $field) {
				$data[$field] = $this->params[$field]->$var;
			}
			return $data;
		}
	}
	
	public function getErrorMsgs($separator=null)
	{
		$data = array();
		foreach (array_keys($this->params) as $field) {
			if ($this->params[$field]->isError()) {
				$data[$field] = $this->params[$field]->getErrorMsg($separator);
			}
		}
		return $separator === null ? $data : implode($separator, $data);
	}
	
	public function getValues()
	{
		return $this->getFieldVarArray('value');
	}
	
	public function isError()
	{
		return $this->error;
	}
	
	public function isValid()
	{
		return !$this->error;
	}

}
