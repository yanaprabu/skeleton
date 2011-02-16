<?php

class A_Socket_EventListener_FrontController extends A_Socket_EventListener_Abstract
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
	public function onConnect($data)
	{
		$this->runController($data);
	}

	/**
	 * Called when client disconnects
	 * 
	 * @param object $data Message
	 */
	public function onDisconnect($data)
	{
		$this->runController($data);
	}

	/**
	 * Called when client sends a message
	 * 
	 * @param object $data Message
	 */
	public function onMessage($data)
	{
		$this->runController($data);
	}

	/**
	 * Creates the front controller, and dispatches to the action
	 * 
	 * @param object $data Message
	 */
	public function runController($message)
	{
		$Request = new A_Socket_Request($message);
		$Locator = $this->locator;

		$Config = $Locator->get('Config');
		
		$Locator->set('Request', $Request);
		
		$front = new A_Controller_Front($Config->get('APP'), $Config->get('DEFAULT_ACTION'), $Config->get('ERROR_ACTION'));
		$front->run($Locator);
	}
}
