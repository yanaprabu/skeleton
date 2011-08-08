<?php
/**
 * Base.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Controller_Helper_Base
 * 
 * Abstract class to be extended to create application helper classes
 * 
 * @package A_Controller
 */
abstract class A_Controller_Helper_Base
{
	
	/**
	 * calling controller
	 * @var A_Controller_Action object
	 */
	protected $controller = null;
   	
	public function __construct(A_Controller_Action $controller)
	{
		$this->controller = $controller;
	}
	
	/**
	 * Return instance of calling controller
	 * 
	 * @return A_Controller_Action
	 */
	public function getController() {
		return $this->controller;
	}

}
