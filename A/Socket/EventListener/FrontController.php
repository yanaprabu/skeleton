<?php
/**
 * FrontController.php
 *
 * @package  A_Socket
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Jonah Dahlquist <jonah@nucleussystems.com>
 */

/**
 * A_Socket_EventListener_FrontController
 *
 * Handles events fired by the Server, and delegates to the Skeleton Front
 * Controller.
 */
class A_Socket_EventListener_FrontController
{

	/**
	 *
	 * @var A_Locator
	 */
	protected $locator;

	/**
	 * Constructor
	 * 
	 * @param A_Locator $locator Locator object passed from bootstrap
	 */
	public function  __construct($locator)
	{
		$this->locator = $locator;
	}

	/**
	 * Called when client connects
	 *
	 * @param object $data Message
	 */
	public function onConnect($event, $data)
	{
		$this->runController($data);
	}

	/**
	 * Called when client disconnects
	 * 
	 * @param object $data Message
	 */
	public function onDisconnect($event, $data)
	{
		$this->runController($data);
	}

	/**
	 * Called when client sends a message
	 * 
	 * @param object $data Message
	 */
	public function onMessage($event, $data)
	{
		$this->runController($data);
	}

	/**
	 * Creates the front controller, and dispatches to the action
	 * 
	 * @param object $data Message
	 */
	protected function runController($message)
	{
		$Request = new A_Socket_Request($message);
		$Locator = $this->locator;

		$Config = $Locator->get('Config');
		
		$Locator->set('Request', $Request);
		
		$front = new A_Controller_Front($Config->get('APP'), $Config->get('DEFAULT_ACTION'), $Config->get('ERROR_ACTION'));
		$front->run($Locator);
	}
}
