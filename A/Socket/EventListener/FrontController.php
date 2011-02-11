<?php

class A_Socket_EventListener_FrontController extends A_Socket_EventListener_Abstract
{

	private $connectRequest;
	private $disconnectRequest;
	
	public function  __construct($locator, $connectRequest, $disconnectRequest)
	{
		parent::__construct($locator);
		$this->connectRequest = $connectRequest;
		$this->disconnectRequest = $disconnectRequest;
	}
	
	public function onConnect($data)
	{
		$this->runController($this->connectRequest);
	}
	
	public function onDisconnect($data)
	{
		$this->runController($this->disconnectRequest);
	}
	
	public function onMessage($data)
	{
		$this->runController(new A_Socket_Request($data));
	}

	public function runController($request)
	{
		$Locator = $this->_locator;

		$Config = $Locator->get('Config');
		
		$Locator->set('Request', $Request);
		
		$front = new A_Controller_Front($Config->get('APP'), $Config->get('DEFAULT_ACTION'), $Config->get('ERROR_ACTION'));
		$front->run($Locator);
	}
}
