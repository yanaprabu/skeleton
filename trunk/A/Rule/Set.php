<?php
/**
 * Contains multiple rules that must all pass for this to be isValid 
 * 
 * @package A_Rule 
 */

class A_Rule_Set {

    protected $chain = array();
    protected $excludes = array();		// array of names not to be validated
    protected $errorMsg = array();
    protected $dir = 'A_Rule_';
    
    public function addRule($rule, $fields=array(), $errorMsgs=array()) {
		// passing rule name to be instatiated later means rest of params are args for the rule
		if (is_string($rule)) {
		    $rule = func_get_args();
		    $fields = null;
		} else {
			if (! is_array($fields)) {
			    $fields = array($fields);
			}
			if (! is_array($errorMsgs)) {
			    $fields = array($errorMsgs);
			}
		}
		if ($fields) {
			$errMsg = current($errorMsgs);
			foreach ($fields as $field) {
				$rule->setName($field);
				$rule->setErrorMsg($errMsg);
				$this->chain[] = $rule;
				$rule = clone $rule;
				if (next($errorMsgs)) {
					$errMsg = current($errorMsgs);
				}
			}
		} else {
			$this->chain[] = $rule;
		}
		return $this;
    }

    public function exclude($names=array()) {
		if (is_string($names)) {
		    $names = array($names);
		}
		$this->excludes = $names;
		return $this;
    }
    
    /**
     * Sets whether fields allow null values or not
     * 
     * @param boolean
     * @return instance of this object (for fluent interface)
     */
	public function setOptional($names, $tf=true) {
		if (! is_array($names)) {
			$names = array($names);
		}
		foreach ($names as $name) {
			foreach ($this->chain as $key => $rule) {
				if ($key == $name) {
					$rule->setOptional($tf);
				}
			}
		}
		return $this;
	}
    /**
     * Whether field is allows null values or not
     * 
     * @return boolean value of optional flag
     */
	public function isOptional($name) {
		$optional = true;
		foreach ($this->chain as $key => $rule) {
			if (($key == $name) && ! $rule->isOptional()) {
				$optional = false;
				break;
			}
		}
		return $optional;
	}

    /**
     * Tells whether this rule passes or not
     * 
     * @return boolean true if pass, fail otherwise
     */
	public function isValid($container) {
		$this->errorMsg = array();
		foreach ($this->chain as $key => $rule) {
		    if ($this->excludes && (in_array($rule->getName(), $this->excludes))) {
			    continue;
		    }
		    // class names with params are added as arrays
		    if (is_array($rule)) {
				$name = array_shift($rule);
				// can use built-in rules and $this->dir will be used
				if(strstr($name, '_') === false) {
				    $name = $this->dir . ucfirst($name);
				}
				include_once str_replace('_', '/', $name) . '.php';
				$ref = new ReflectionClass($name);
				$rule = $ref->newInstanceArgs($rule);
				$this->chain[$key] = $rule;
				unset($ref);
		    }
		    if (! $this->chain[$key]->isValid($container)) {
				$errorMsg = $this->chain[$key]->getErrorMsg();
				if (is_array($errorMsg)) {
					$this->errorMsg = array_merge($this->errorMsg, $errorMsg);
				} else {
					$this->errorMsg[$this->chain[$key]->getName()][] = $errorMsg;
				}
		    }
		}
		return empty($this->errorMsg);
    }
    
    public function getErrorMsg($separator=null) {
 		return $separator === null ? $this->errorMsg : implode($separator, $this->errorMsg);
    }

}