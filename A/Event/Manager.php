<?php

/**
 * Event Manager
 * 
 * Handles creating, storing, and firing of events.
 * 
 * @author Jonah <jonah[at]nucleussystems[dot]com>
 */
class A_Event_Manager
{

	private $_events = array();
	
	const TYPE_MULTILISTENER = 0;
	const TYPE_LISTENER = 1;
	const TYPE_CALLBACK = 2;
	const ERROR_WRONGTYPE = 'The only types supported by A_Event_Manager are A_Event_Listener, A_Event_MultiListener, and callback.';
	
	public function __construct()
	{
		
	}
	
	/**
	 * Add event listener.  After being called, that eventName can be triggered
	 * with fireEvent.
	 * 
	 * @param string $eventName
	 * @param A_Event_Listener $eventListener
	 */
	public function addEventListener($eventListener, $eventName = null)
	{
		$type = $this->getEventType($eventListener);
		
		if ($type == self::TYPE_LISTENER || $type == self::TYPE_CALLBACK) {
			
			$this->addOneListener($eventListener, $eventName);
			
		} elseif ($type == self::TYPE_MULTILISTENER) {
			
			$events = array();
			
			if (is_array($eventName)) {
				$events = $eventName;
			} else {
				$events = $eventListener->getEvents();
			}
			
			foreach ($events as $event) {
				$this->addOneListener($eventListener, $event);
			}
			
		} else {
			throw new Exception(self::ERROR_WRONGTYPE);
		}
	}
	
	/**
	 * Adds one event listener to one event
	 * 
	 * @param mixed $eventListener
	 * @param string $eventName
	 */
	private function addOneListener($eventListener, $eventName)
	{
		$eventName = strval($eventName);
		
		if (!isset($this->_events[$eventName])) {
			$this->_events[$eventName] = array();
		}
		$this->_events[$eventName][] = $eventListener;
	}
	
	/**
	 * Removes all listeners for the given event
	 * 
	 * @param string $eventName
	 */
	public function killEvent(string $eventName)
	{
		unset($this->_events[$eventName]);
	}
	
	/**
	 * Removes event listener.  If no more listeners are left on that event,
	 * the event itself is removed.
	 * 
	 * @param A_Event_Listener $eventListener
	 */
	public function removeEventListener(A_Event_Listener $eventListener)
	{
		foreach ($this->_events as $ek => $event) {
			foreach ($event as $lk => $listener) {
				if ($listener == $eventListener) {
					unset($this->_events[$ek][$lk]);
				}
			}
		}
	}
	
	/**
	 * Fires the event of the given name.  The object (optional) is passed to
	 * the event handler.
	 * 
	 * @param string $eventName
	 * @param object $eventObject
	 */
	public function fireEvent($eventName, object $eventObject = null)
	{
		if (isset($this->_events[$eventName])) {
			$event = $this->_events[$eventName];
			foreach ($event as $listener) {
				$type = $this->getEventType($listener);
				if ($type == self::TYPE_CALLBACK) {
					call_user_func($listener, $eventName, $eventObject);
				} else {
					$listener->onEvent($eventName, $eventObject);
				}
			}
		}
	}
	
	/**
	 * Finds out what type an event object is
	 * 
	 * @param mixed $event Event to asses
	 * @access private
	 */
	private function getEventType($event)
	{
		if (is_callable($event)) {
			return self::TYPE_CALLBACK;
		} elseif (is_object($event)) {
			if ($event instanceOf A_Event_Listener) {
				return self::TYPE_LISTENER;
			} elseif ($event instanceOf A_Event_MultiListener) {
				return self::TYPE_MULTILISTENER;
			}
		}
		return false;
	}
}