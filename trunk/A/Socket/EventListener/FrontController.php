<?php

class A_Socket_EventListener_FrontController extends A_Socket_EventListener_Abstract
{

	public function  __construct($locator)
	{
		parent::__construct($locator);
	}
	
	public function onConnect($data)
	{
		$data->setSession(new A_Collection());
	}
	
	public function onDisconnect($data)
	{
		
	}
	
	public function onMessage($data)
	{
		$Locator = $this->_locator;

		$Config = $Locator->get('Config');
		
		$Request = new A_Socket_Request($data);
		$Locator->set('Request', $Request);
		
		$front = new A_Controller_Front($Config->get('APP'), $Config->get('DEFAULT_ACTION'), $Config->get('ERROR_ACTION'));
		$front->run($Locator);
	}
}
