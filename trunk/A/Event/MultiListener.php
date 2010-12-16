<?php

/**
 * Multi-event Listener
 * 
 * Interface for handlers handling multiple events
 * 
 * @author Jonah <jonah[at]nucleussystems[dot]com>
 */
interface A_Event_MultiListener
{
	/**
	 * Needed by Manager.  Must return an array of strings representing all
	 * events this object wishes to handle
	 * 
	 * @return array() Events to handle
	 */
	public function getEvents();
	
	/**
	 * Called when event is fired that's defined in getEvents()
	 * 
	 * @param string $eventName
	 * @param object $eventObject
	 */
	public function onEvent($eventName, $eventObject = null);
}