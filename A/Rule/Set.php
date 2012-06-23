<?php
/**
 * Set.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Rule_Set
 *
 * Contains multiple rules that must all pass for this to be isValid
 *
 * @package A_Rule
 */
class A_Rule_Set
{

	protected $chain = array();
	protected $excludes = array();		// array of names not to be validated
	protected $includes = array();		// array of names only to be validated
	protected $errorMsg = array();
	protected $dir = 'A_Rule_';

	public function addRule($rules, $fields=array(), $errorMsgs=array())
	{
		if (!is_array($rules)) {
			$rules = array($rules);
		}
		foreach ($rules as $rule) {
			// passing rule name to be instatiated later means rest of params are args for the rule
			if (is_string($rule)) {
				$rule = func_get_args();
				$fields = null;
			} else {
				if (!is_array($fields)) {
					$fields = array($fields);
				}
				if (!is_array($errorMsgs)) {
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
		}
		return $this;
	}

	public function excludeRules($names=array())
	{
		if (is_string($names)) {
			$names = array($names);
		}
		$this->excludes = $names;
		return $this;
	}

	public function includeRules($names=array())
	{
		if (is_string($names)) {
			$names = array($names);
		}
		$this->includes = $names;
		return $this;
	}

	/**
	 * Remove all assigned rules
	 */
	public function clearRules()
	{
		$this->chain = array();
	}

	/**
	 * Sets whether fields allow null values or not
	 *
	 * @param array $names
	 * @param bool $tf
	 * @return $this
	 */
	public function setOptional($names, $tf=true)
	{
		if (!is_array($names)) {
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
	 * @param string $name
	 * @return bool Value of optional flag
	 */
	public function isOptional($name)
	{
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
	 * @param object $container
	 * @return bool
	 */
	public function isValid($container)
	{
		$this->errorMsg = array();

		// load helpers specified by array('class_name', 'param', ...)
		foreach ($this->chain as $key => $args) {
			// class names with params are added as arrays
			if (is_array($this->chain[$key])) {
				$name = array_shift($args);
				// can use built-in rules and $this->dir will be used
				if (strstr($name, '_') === false) {
					$name = $this->dir . ucfirst($name);
				}
				$ref = new ReflectionClass($name);
				$this->chain[$key] = $ref->newInstanceArgs($args);
				unset($ref);
			}
		}

		// in only for specific fields the build chain containing only those names
		if ($this->includes) {
			$chain = array();
			foreach ($this->chain as $key => $rule) {
				if (!($rule instanceof A_Rule_Set) && (in_array($rule->getName(), $this->includes))) {
					$chain[$key] = $key;
				}
			}
		} else {
			$chain = array_keys($this->chain);
		}

		// if excludes certain fields then remove rules with those names from the chain
		if ($this->excludes) {
			$keys = array_keys($chain);
			foreach ($keys as $key) {
				if (!($this->chain[$key] instanceof A_Rule_Set) && (in_array($this->chain[$key]->getName(), $this->excludes))) {
					unset($chain[$key]);
				}
			}
		}

		// check each rule in chain and gather error messages
		foreach ($chain as $key) {
			if (!$this->chain[$key]->isValid($container)) {
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

	public function getErrorMsg($separator=null)
	{
 		return $separator === null ? $this->errorMsg : implode($separator, $this->errorMsg);
	}

}
