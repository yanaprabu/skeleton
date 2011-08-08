<?php
/**
 * Transition.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Controller_App_Transition
 * 
 * Application controller class to hold transition information.
 * 
 * @package A_Controller
 */
class A_Controller_App_Transition
{

	public $fromState;
	public $toState;
	public $rule;
	public $condition;
	
	public function __construct($fromState, $toState, $rule, $condition=true)
	{
		$this->fromState = $fromState;
		$this->toState = $toState;
		$this->rule = $rule;
		$this->condition = $condition;
	}
	
	public function getToState()
	{
		return $this->toState;
	}
	
}
