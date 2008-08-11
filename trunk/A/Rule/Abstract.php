<?php
/**
 * Abstract base class for validation rules 
 * 
 * @package A_Validator 
 */

abstract class A_Rule_Abstract {

	protected $container;
	protected $field;
	protected $errorMsg;
	protected $optional = false;
	
    /**
     * When creating children here, remember to make $field the first param and
     * $errorMsg the last param. All other params should be in the middle
     * 
     * @param string field this rule applies to
     * @param string error message to be returned if validation fails
     */
	public function __construct($field, $errorMsg) {
		$this->field = $field;
		$this->errorMsg = $errorMsg;
	}
    /**
     * Changes the field this rule applies to
     * 
     * @param string field this rule applies to
     * @return instance of this object (for fluent interface)
     */
	public function setName($field) {
		$this->field = $field;
		return $this;
	}
    /**
     * Returns the field this rule applies to
     * 
     * @return string field this rule applies to
     */
	public function getName() {
		return $this->field;
	}
    /**
     * Sets whether field allows null values or not
     * 
     * @param boolean
     * @return instance of this object (for fluent interface)
     */
	public function setOptional($tf) {
		$this->optional = $tf;
		return $this;
	}
    /**
     * Whether field is allows null values or not
     * 
     * @return boolean value of optional flag
     */
	public function isOptional() {
		return $this->optional;
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
			$name = $this->field;
		}
		if (is_array($this->container)) {
			return $this->container[$name];
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
     * @return A_Rule_Abstract returns this instance for fluent interface
     */
	public function setErrorMsg($errorMsg) {
		$this->errorMsg = $errorMsg;
		return $this;
	}
    /**
     * Gets the error message that is to be returned if this rule should fail
     * 
     * @return string error message
     */
	public function getErrorMsg() {
		return $this->errorMsg;
	}
    /**
     * Tells whether this rule passes or not
     * 
     * @return boolean true if pass, fail otherwise
     */
	public function isValid($container) {
	    $this->container = $container;
	    if ($this->optional && $this->isNull()) {
	        return true;
	    } else {
	        return $this->validate($container);
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
