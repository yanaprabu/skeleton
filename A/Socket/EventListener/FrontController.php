<?php

class A_Socket_EventListener_FrontController extends A_Socket_EventListener_Abstract
{
	
	public function  __construct($locator)
	{
		parent::__construct($locator);
	}
	
	public function onConnect($data)
	{
		$this->runController($data);
	}
	
	public function onDisconnect($data)
	{
		$this->runController($data);
	}
	
	public function onMessage($data)
	{
		$this->runController($data);
	}

	public function runController($message)
	{
		$Request = new A_Socket_Request($message);
		$Locator = $this->_locator;

		$Config = $Locator->get('Config');
		
		$Locator->set('Request', $Request);
		
		$front = new A_Controller_Front($Config->get('APP'), $Config->get('DEFAULT_ACTION'), $Config->get('ERROR_ACTION'));
		$front->run($Locator);
	}
}
