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
	const ERROR_NO_EVENT = 'No event specified. ';
	const ERROR_NO_METHOD = 'Listener has no onEvent() method . ';
	const ERROR_WRONG_TYPE = 'The only types callback, closure and object with onEvent() method supported. ';
	
	protected $_events = array();
	protected $_exception = '';							// A_Db_Exception
	protected $_errorMsg = '';
	
	public function __construct($exception='')
	{
		$this->setException($exception);
	}
	
	public function setException($class)
	{
		if ($class === true) {
			$this->_exception = 'A_Db_Exception';
		} else {
			$this->_exception = $class;
		}
	}	

	/**
	 * Add event listener.  After being called, that eventName can be triggered
	 * with fireEvent.
	 * 
	 * @param string $eventName
	 * @param A_Event_Listener $eventListener
	 */
	public function addEventListener($eventListener, $events = null)
	{
		// if no events passed then check if we can get them from the listener
		if (!$events && method_exists($eventListener, 'getEvents')) {
			$events = $eventListener->getEvents();
		}
		
		if ($events) {
			// if single event name passed then convert to array for foreach below
			if (is_string($events)) {
				$events = array($events);
			}
			
			foreach ($events as $event) {
				$event = strval($event);
				
				if (!isset($this->_events[$event])) {
					$this->_events[$event] = array();
				}
				$this->_events[$event][] = $eventListener;
			}
			
		} else {
			$this->_errorHandler(0, self::ERROR_NO_EVENT);
		}
	}
	
	/**
	 * Removes all listeners for the given event
	 * 
	 * @param string $eventName
	 */
	public function killEvent(string $eventName)
	{
		if (isset($this->_events[$eventName])) {
			unset($this->_events[$eventName]);
		}
		return $this;
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
		return $this;
	}
	
	/**
	 * Fires the event of the given name.  The object (optional) is passed to
	 * the event handler.
	 * 
	 * @param string $eventName
	 * @param mixed $eventData	any data you want to pass to listeners
	 */
	public function fireEvent($eventName, $eventData = null)
	{
		if (isset($this->_events[$eventName])) {
			$event = $this->_events[$eventName];
			foreach ($event as $listener) {

				if (is_array($listener)) {									// callback array
					call_user_func($listener, $eventName, $eventData);
				
				} elseif (is_object($listener)) {
					if ($listener instanceof Closure) {						// anonymous function
						$listener($eventName, $eventData);
					
					} elseif (method_exists($listener, 'onEvent')) {												// standard A_Event_Listener
						$listener->onEvent($eventName, $eventData);
					} else {
						$this->_errorHandler(0, self::ERROR_NO_METHOD);
					}
				} else {
					$this->_errorHandler(0, self::ERROR_WRONG_TYPE);
				}
			}
		}
	}
	
	public function _errorHandler($errno, $errorMsg) {
		$this->_errorMsg .= $errorMsg;
		if ($this->_exception) {
			throw A_Exception::getInstance($this->_exception, $errorMsg);
		}
	}	

	public function getErrorMsg() {
		return $this->_errorMsg;
	}
}