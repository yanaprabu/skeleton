<?php
/**
 * Abstract.php
 *
 * @package  A_Socket_EventListener
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Jonah Dahlquist <jonah@nucleussystems.com>
 */

/**
 * Handle events from the Server
 */
abstract class A_Socket_EventListener_Abstract implements A_Event_Listener
{

	/**
	 * @return array List of events that this listener handles
	 */
	public function getEvents()
	{
		return array('a.socket.onconnect', 'a.socket.ondisconnect', 'a.socket.onmessage');
	}

	/**
	 * When event is fired
	 * 
	 * @param string $eventName Name of event fired
	 * @param object $eventData Message sent
	 */
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
