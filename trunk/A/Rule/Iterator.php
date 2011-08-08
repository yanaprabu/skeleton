<?php
/**
 * Iterator.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Rule_Iterator
 * 
 * Rule to check a rule against an array of values.
 * 
 * @package A_Rule
 */
class A_Rule_Iterator extends A_Rule_Base
{

	protected $rule;
	protected $params = array(
		'rule' => null, 
		'field' => '', 
		'errorMsg' => '', 
		'optional' => false
	);
	
	public function get($name)
	{
	  return $this->value;
	}
	
	protected function validate()
	{
		$data = $this->getValue();
		$result = false;
		if (is_array($data)) {
			foreach ($data as $value) {
				$this->value = $value;		// to allow access to $this->get() above
				if (! $this->params['rule']->isValid($this)) {
					return false;
				}
			}
			return true;
		} else {
			return ($this->params['rule']->validate($this->getValue()));
		}
	}

}
