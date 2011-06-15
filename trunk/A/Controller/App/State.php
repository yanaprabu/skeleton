<?php
/**
 * State.php
 *
 * @package  A_Controller
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Controller_App_State
 * 
 * Application controller class to hold state and callback
 */
class A_Controller_App_State
{

	public $name;
	public $handler;
	
	public function __construct($name, $handler)
	{
		$this->name = $name;
		$this->handler = $handler;
	}

}
