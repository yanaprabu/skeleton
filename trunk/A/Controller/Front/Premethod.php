<?php
/**
 * Premethod.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Controller_Front_Premethod
 * 
 * Create object of this class to pass to Front Controller preFilter() method to call the method named $method, if it exists, before action is dispatched.  The method may return an array which will be returned to the Front Controller to short circuit dispatch. Or it may return true and then the $change_action array containing dir/class/method/args is returned.
 * 
 * @package A_Controller
 */
class A_Controller_Front_Premethod
{

	protected $method;
	protected $change_action;
	protected $locator;
	
	public function __construct($method, $change_action, $locator)
	{
		$this->method = $method;
		$this->change_action = $change_action;
		$this->locator = $locator;
	}
	
	public function run($controller)
	{
		$change_action = null;
		if (method_exists($controller, $this->method)) {
			// pre-execute method if it exists 
			$change_action = $controller->{$this->method}($this->locator);
			if ($change_action && !is_array($change_action)) {
				$change_action = $this->change_action;
			}
		}
		return $change_action;
	}

}
