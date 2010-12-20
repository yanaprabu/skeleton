<?php

abstract class A_WebSocket_EventListener_Abstract implements A_Event_Listener
{

	public function getEvents()
	{
		return array('a.websocket.onconnect', 'a.websocket.ondisconnect', 'a.websocket.onmessage');
	}
	
	public function onEvent($eventName, $eventData)
	{
		switch ($eventName) {
			case 'a.websocket.onconnect':
				$this->onConnect($eventData);
			break;
			case 'a.websocket.ondisconnect':
				$this->onDisconnect($eventData);
			break;
			case 'a.websocket.onmessage':
				$this->onMessage($eventData);
			break;
		}
	}
	
	public abstract function onConnect($eventData);
	public abstract function onDisconnect($eventData);
	public abstract function onMessage($eventData);
}