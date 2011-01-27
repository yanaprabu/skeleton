<?php

class A_Socket_EventListener_FrontController extends A_Socket_EventListener_Abstract
{

	public function onConnect($data)
	{
		$data->setSession(new A_Collection());
	}
	
	public function onDisconnect($data)
	{
		
	}
	
	public function onMessage($data)
	{
		$request = new A_WebSocket_Request($data);
		$locator = new A_Locator();
		$locator->set('Request', $request);
		
		$front = new A_Controller_Front(dirname(dirname(dirname(dirname(__FILE__)))) . '/examples/websocket/app', array('', 'main', 'main'), array('', 'main', 'main'));
		$front->run($locator);
	}
}
