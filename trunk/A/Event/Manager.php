<?php
/**
 * Manager.php
 *
 * @package  A_Event
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Jonah Dahlquist <jonah@nucleussystems.com>, Christopher Thompson <christopherxthompson@gmail.com>
 */

/**
 * Event Manager
 * 
 * Handles creating, storing, and firing of events.
 */
class A_Event_Manager
{

	const ERROR_NO_EVENT = 'No event specified. ';
	const ERROR_NO_METHOD = 'Listener has no onEvent() method . ';
	const ERROR_WRONG_TYPE = 'The only types callback, closure and object with onEvent() method supported. ';
	
	protected $_events = array();
	protected $_cancel = false;							// Listeners can return this value to stop chain
	protected $_exception = '';							// A_Exception
	protected $_errorMsg = '';
	protected $_path = './events';
	
	public function __construct($exception='')
	{
		$this->setException($exception);
	}
	
	/**
	 * Set the exception class to use.  If $class is set to true, the default
	 * is used.
	 * 
	 * @param string $class
	 */
	public function setException($class)
	{
		if ($class === true) {
			$this->_exception = 'A_Exception';
		} else {
			$this->_exception = $class;
		}
		
		return $this;
	}	

	/**
	 * Set return value that stops the Listener chain
	 * is used.
	 * 
	 * @param string $class
	 */
	public function setCancel($cancel)
	{
		$this->_cancel = $cancel;
		return $this;
	}	

	/**
	 * Add event listener.  After being called, that eventName can be triggered
	 * with fireEvent.
	 * 
	 * @param mixed $events
	 * @param mixed $eventListener
	 */
	public function addEventListener($events, $eventListener = null)
	{
		if (!$eventListener) {
			$eventListener = $events;
			$events = null;
		}
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
		
		return $this;
	}
	
	/**
	 * Removes all listeners for the given event
	 * 
	 * @param string $eventName
	 */
	public function killEvent($eventName)
	{
		unset($this->_events[$eventName]);
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
	 * @param mixed $eventData any data you want to pass to listeners
	 */
	public function fireEvent($eventName, $eventData = null)
	{
		$result = array();
		if (isset($this->_events[$eventName])) {
			$i = 0;
			foreach ($this->_events[$eventName] as $listener) {
				if (is_callable($listener)) {
					// callback/anonymous function
					$r = call_user_func($listener, $eventName, $eventData);				
				} elseif (is_object($listener)) {
					// event listener interface
					if (method_exists($listener, 'onEvent')) {												// standard A_Event_Listener
						$r = $listener->onEvent($eventName, $eventData);					
					} else {
						$this->_errorHandler(0, self::ERROR_NO_METHOD);
						$r = null;
					}
				} else {
					if ($this->loadClass($listener)) {
						$listener = new $listener();
						if (method_exists($listener, 'onEvent')) {
							$r = $listener->onEvent($eventName, $eventData);
						} else {
							$this->_errorHandler(0, self::ERROR_NO_METHOD);
							$r = null;
						}
					} else {
						$this->_errorHandler(0, self::ERROR_WRONG_TYPE);
						$r = null;
					}
				}
				// only use result for non-null return values
				if ($r !== null) {
					if ($r === $this->_cancel) {
						break;
					} else {
						$result[$i] = $r;
					}
				}
				++$i;
			}
		} else {
			$this->_errorHandler(0, self::ERROR_NO_EVENT);
		}
		return $result;
	}
	
	/**
	 * Gets any errors accumulated
	 * 
	 * @return string
	 */
	public function getErrorMsg() {
		return $this->_errorMsg;
	}

	/**
	 * Set path to look in for handler classes
	 *
	 * @param string $path Directory to search in
	 */
	public function setPath($path)
	{
		$this->_path = $path;
	}
	
	/**
	 * Creates and throws an exception of the defined type
	 * 
	 * @param int $errno
	 * @param string $errorMsg
	 */
	private function _errorHandler($errno, $errorMsg) {
		$this->_errorMsg .= $errorMsg;
		if ($this->_exception) {
			throw A_Exception::getInstance($this->_exception, $errorMsg);
		}
	}
	
	/**
	 * Loads a class from the path
	 *
	 * @param string $class
	 * @return bool Success or failure
	 */
	protected function loadClass($class) {
		$file = rtrim($this->_path, '/\\') . '/'. str_replace(array('_','\\','-'), array('/','/','_'), ltrim($class, '\\')) . '.php';
		if (class_exists($class)) {
			return true;
		} elseif (file_exists($file)) {
			require_once($file);
			return true;
		} else {
			return false;
		}
	}

}
