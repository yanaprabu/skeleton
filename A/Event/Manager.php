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

	private $_events;
	
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
	public function addEventListener(string $eventName, A_Event_Listener $eventListener)
	{
		
	}
	
	/**
	 * Add multi-event listener.  This allows one object to listen to multiple
	 * events.
	 * 
	 * @param A_Event_MultiListener $eventMultiListener
	 */
	public function addEventMultiListener(A_Event_MultiListener $eventMultiListener)
	{
		
	}
	
	/**
	 * Removes all listeners for the given event
	 * 
	 * @param string $eventName
	 */
	public function killEvent(string $eventName)
	{
		
	}
	
	/**
	 * Removes event listener.  If no more listeners are left on that event,
	 * the event itself is removed.
	 * 
	 * @param A_Event_Listener $eventListener
	 */
	public function removeEventListener(A_Event_Listener $eventListener)
	{
		
	}
	
	/**
	 * Fires the event of the given name.  The object (optional) is passed to
	 * the event handler.
	 * 
	 * @param string $eventName
	 * @param object $eventObject
	 */
	public function fireEvent(string $eventName, object $eventObject = null)
	{
		
	}
}