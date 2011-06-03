<?php
/**
 * Abstract.php
 *
 * @package  A_Rule
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Rule_Abstract
 * 
 * Abstract base class for validation rules 
 */
abstract class A_Rule_Base {

	protected $container;
	/*
	 * $params array define the order and names of the constructor params
	 */
	protected $params = array(
							'field' => '', 
							'errorMsg' => '', 
							'optional' => false
							);
	
    /**
     * When creating children here, remember to call this function and 
     * put params before $field and $errorMsg.
     * 
     * @param string field this rule applies to
     * @param string error message to be returned if validation fails
     * @param boolean whether this rule returns true for null value
     *      */
	public function __construct(/* $field='', $errorMsg='', $optional=false*/) {
		$params = func_get_args();
		if (count($params) == 1 && is_array($params[0])) {
			// first param is array of params
			$this->config($params[0]);
		} else {
			reset($this->params);
			foreach ($params as $value) {
				// set the values in params in order
				$this->params[key($this->params)] = $value;
				next($this->params);
			}
		}
	}
    /**
     * Set params property with assoc array
     * 
     * @param array $params
     * @return instance of this object (for fluent interface)
     */
	public function config($params=array()) {
		if (!is_array($params)) {
			$params = array($params);
		}
		foreach ($params as $key => $value) {
			$this->params[$key] = $value;
		}
		return $this;
	}
	/**
     * Changes the field this rule applies to
     * 
     * @param string field this rule applies to
     * @return instance of this object (for fluent interface)
     */
	public function setName($field) {
		$this->params['field'] = $field;
		return $this;
	}
    /**
     * Returns the field this rule applies to
     * 
     * @return string field this rule applies to
     */
	public function getName() {
		return $this->params['field'];
	}
    /**
     * Sets whether field allows null values or not
     * 
     * @param boolean
     * @return instance of this object (for fluent interface)
     */
	public function setOptional($tf=true) {
		$this->params['optional'] = $tf;
		return $this;
	}
    /**
     * Whether field is allows null values or not
     * 
     * @return boolean value of optional flag
     */
	public function isOptional() {
		return $this->params['optional'];
	}
	/**
     * Returns the value associated with this rule by default, but can return any value in
     * the data container that this rule is validating
     * 
     * @param string field you're trying to access
     * @return mixed whatever is inside the container array at key $name
     */
	public function getValue($name = null) {
		if (is_null($name)) {
			$name = $this->params['field'];
		}
		if (is_array($this->container)) {
			return (isset($this->container[$name])) ? $this->container[$name] : null;
		} elseif (is_object($this->container)) {
			return $this->container->get($name);
		} else {
			return $this->container;
		}
	}
    /**
     * Sets the error message that is to be returned if this rule should fail
     * 
     * @param string error message
     * @return this instance for fluent interface
     */
	public function setErrorMsg($errorMsg) {
		$this->params['errorMsg'] = $errorMsg;
		return $this;
	}
    /**
     * Gets the error message that is to be returned if this rule should fail
     * 
     * @return string error message
     */
	public function getErrorMsg() {
		if (strpos($this->params['errorMsg'], '{') === false) {
			// no template replacement {tags} in string
			return $this->params['errorMsg'];
		} else {
			// replace template {tags} in string
			$errorMsg = $this->params['errorMsg'];
			foreach ($this->params as $key => $value) {
				$errorMsg = str_replace('{'.$key.'}', $value, $errorMsg);
			}
			return $errorMsg;
		}
	}
    /**
     * Tells whether this rule passes or not
     * 
     * @return boolean true if pass, fail otherwise
     */
	public function isValid($container) {
	    $this->container = $container;
	    if ($this->params['optional'] && $this->isNull()) {
	        return true;
	    } else {
	        return $this->validate();
	    }
	}
    /**
     * Tells whether the value is '' or null
     * 
     * @return boolean true if value is '' or null, otherwise false
     */
	public function isNull() {
	    return ($this->getValue() === '') || ($this->getValue() === null);
	}
    /**
     * Tells whether this rule passes or not (delegated to child class)
     * 
     * @return boolean true if pass, fail otherwise
     */
	abstract protected function validate();

}
