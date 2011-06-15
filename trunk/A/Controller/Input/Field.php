<?php
/**
 * Field.php
 *
 * @package  A_Controller
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Controller_Input_Field
 * 
 * Input field class with request filtering and validation
 */
class A_Controller_Input_Field
{

	public $name = '';
	public $value = '';
	public $filters = null;
	public $rules = null;
	public $errorMsg = array();
	public $renderer = null;
	public $error = false;
	
	public function __construct($name)
	{
		$this->name = $name;
	}
	
	public function addFilter($filter)
	{
		$this->filters[] = $filter;
	}
	
	public function addRule($rule)
	{
		$this->rules[] = $rule;
	}
	
	public function getValue()
	{
		return $this->value;
	}
	
	public function setValue($value)
	{
		$this->value = $value;
		return $this;
	}
	
	public function setRenderer($renderer)
	{
		$this->renderer = $renderer;
		return $this;
	}
	
	public function getErrorMsg($separator=null)
	{
		if ($separator === null) {
			return $this->errorMsg;
		} else {
			if (is_array($this->errorMsg)) {
				return implode($separator, $this->errorMsg);
			}
		}
	}
	
	/**
	 * Add an error or clear errors by passing null
	 * 
	 * @param string $value
	 * @return $this
	 */
	public function setError($value='')
	{
		if ($value !== null) {
			if (is_array($value)) {
				$this->errorMsg = array_merge($this->errorMsg, $value);
			} else {
				$this->errorMsg[] = $value;
			}
			$this->error = true;
		} else {
			$this->errorMsg = array();
			$this->error = false;
		}
		return $this;
	}
	
	public function isError()
	{
		return $this->error;
	}
	
	public function isValid()
	{
		return !$this->error;
	}
	
	public function render()
	{
		if (isset($this->type['renderer'])) {
			if (!isset($this->renderer)){
				$this->renderer = $this->type['renderer'];
				unset($this->type['renderer']);
			}
		}
		// string is name of class with underscores in loadable convention
		if (is_string($this->renderer)){
			// instantiate render object if class name given
			$this->renderer = new $this->renderer();
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
