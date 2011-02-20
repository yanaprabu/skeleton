<?php
/**
 * EventListener.php
 *
 * @package  A_Socket
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Jonah Dahlquist <jonah@nucleussystems.com>
 */

/**
 * A_Socket_EventListener
 *
 * Interface defining Server event handler.
 */
interface A_Socket_EventListener
{
	/**
	 * Called when client connects
	 *
	 * @param $event Name of event fired
	 * @param $message Message sent with event
	 */
    public function onConnect($event, $message);

	/**
	 * Called when client sends message
	 *
	 * @param $event Name of event fired
	 * @param $message Message sent by client
	 */
	public function onMessage($event, $message);

	/**
	 * Called when client disconnects
	 *
	 * @param $event Name of event fired
	 * @param $message Message send with event
	 */
	public function onDisconnect($event, $message);
}