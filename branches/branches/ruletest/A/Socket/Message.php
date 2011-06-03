<?php
/**
 * Message.php
 *
 * @package  A_Socket
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Jonah Dahlquist <jonah@nucleussystems.com>
 */

/**
 * A_Socket_Message
 *
 * Interface defining a Message object.
 */
interface A_Socket_Message
{

	/**
	 * Reply only to the client that sent the message
	 */
	const SENDER = 0;

	/**
	 * Reply to all clients
	 */
	const ALL = 1;

	/**
	 * Reply to all clients except the sender
	 */
	const OTHERS = 2;

	/**
	 * Constructor
	 *
	 * @param mixed $message Raw message data
	 * @param A_Socket_Client Client that sent the message
	 * @param array Array of all connected clients
	 */
	public function __construct($message, $client, $clients);

	/**
	 * Reply to client(s)
	 *
	 * @param mixed $message Message to be converted and sent
	 * @param mixed $recipient Clients to send to.  Can be SENDER, ALL, OTHERS, or a callback that returns a boolean value
	 */
	public function reply($message, $recipient);

	/**
	 * Get raw message data
	 *
	 * @return mixed
	 */
	public function getMessage();

	/**
	 * Get session data associated with sender client
	 *
	 * @return mixed
	 */
	public function getSession();

	/**
	 * Set session data associated with sender client
	 *
	 * @param mixed $session Session data to set
	 */
	public function setSession($session);

	/**
	 * Get an array of all client sessions
	 *
	 * @return array
	 */
	public function getAllSessions();
}
