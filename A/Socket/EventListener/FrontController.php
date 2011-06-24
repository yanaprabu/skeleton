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
 * Handles events fired by the Server, and delegates to the Skeleton Front Controller.
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
	 * Sets the events handled by this object
	 *
	 * @return array
	 */
	public function getEvents()
	{
		return array(A_Socket_Server::EVENT_CONNECT, A_Socket_Server::EVENT_MESSAGE, A_Socket_Server::EVENT_DISCONNECT);
	}
	
	/**
	 * Called when a client connects, sends a message, or disconnects.  Creates
	 * a new Front Controller and dispatches to the controller.
	 * 
	 * @param string $event
	 * @param A_Socket_Message $data
	 */
	public function onEvent($event, $message)
	{
		$Request = new A_Socket_Request($message);
		$Locator = $this->locator;

		$Config = $Locator->get('Config');
		
		$Locator->set('Request', $Request);
		
		$front = new A_Controller_Front($Config->get('APP'), $Config->get('DEFAULT_ACTION'), $Config->get('ERROR_ACTION'));
		$front->run($Locator);
	}

}
