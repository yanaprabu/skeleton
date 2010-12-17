<?php

/**
 * Event Listener object
 * 
 * Interface for listener objects being passed to Manager
 * 
 * @author Jonah <jonah[at]nucleussystems[dot]com>
 */
interface A_Event_Listener
{
	/**
	 * Called when event is fired in Manager
	 * 
	 * @param object $eventObject
	 */
	public function onEvent($eventName, $eventObject);
}