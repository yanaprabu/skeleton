<?php
/**
 * Listener.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	Jonah Dahlquist <jonah@nucleussystems.com>
 */

/**
 * A_Event_Listener
 *
 * Interface for listener objects being passed to Manager
 *
 * @package A_Event
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
