<?php

abstract class A_Socket_EventListener_Abstract implements A_Event_Listener
{

	public function getEvents()
	{
		return array('a.socket.onconnect', 'a.socket.ondisconnect', 'a.socket.onmessage');
	}
	
	public function onEvent($eventName, $eventData)
	{
		switch ($eventName) {
			case 'a.socket.onconnect':
				$this->onConnect($eventData);
			break;
			case 'a.socket.ondisconnect':
				$this->onDisconnect($eventData);
			break;
			case 'a.socket.onmessage':
				$this->onMessage($eventData);
			break;
		}
	}
	
	public abstract function onConnect($eventData);
	public abstract function onDisconnect($eventData);
	public abstract function onMessage($eventData);
}
