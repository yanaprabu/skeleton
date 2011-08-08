<?php
/**
 * Client.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	Jonah Dahlquist <jonah@nucleussystems.com>
 */

/**
 * A_Socket_Client
 *
 * Inteface defining a Client object
 * 
 * @package A_Socket
 */
interface A_Socket_Client
{

	/**
	 * Constructor
	 *
	 * @param resource $socket Socket resource client is connected to
	 */
	public function __construct($socket);
	
	/**
	 * Writes a string message to the socket
	 *
	 * @param string $message Message to send
	 */
	public function send($message);
	
	/**
	 * Extracts messages from data stream
	 *
	 * @param string $data Data read from stream
	 * @return array Messages
	 */
	public function receive($data);
	
	/**
	 * Perform handshake (if any) between server and client to authenticate
	 *
	 * @param string $data Data to use to authenticate
	 */
	public function connect($data);
	
	/**
	 * Check if handshake has been completed
	 *
	 * @return bool
	 */
	public function isConnected();
	
	/**
	 * Get session data associated with client object
	 *
	 * @return mixed
	 */
	public function getSession();
	
	/**
	 * Set session data associated with client object
	 *
	 * @param mixed $session
	 */
	public function setSession($session);

}
