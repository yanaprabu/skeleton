<?php
/**
 * Server.php
 *
 * @package  A_Socket
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Jonah Dahlquist <jonah@nucleussystems.com>
 */

/**
 * Handles socket connect, message, and disconnect events.
 */
interface A_Socket_EventListener
{
    public function onConnect($event, $message);

	public function onMessage($event, $message);

	public function onDisconnect($event, $message);
}