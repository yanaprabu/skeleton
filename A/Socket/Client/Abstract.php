<?php

/**
 * Client
 */
class A_Socket_Client_Abstract
{

	/**
	 * Socket that client is on
	 * @var resource
	 */
	protected $socket;

	/**
	 * If handshake has been completed
	 * @var boolean
	 */
	protected $connected = false;

	/**
	 * User session data.  Developer defined.
	 * @var mixed
	 */
	protected $session;
	
	/**
	 * Constructor
	 *
	 * @param resource $socket Socket that client is on
	 */
	public function __construct($socket)
	{
		$this->socket = $socket;
	}

	/**
	 * Send message to client
	 * 
	 * @param string $message
	 */
	public function send($message)
	{
		$this->_send($message);
	}

	/**
	 * Write data to socket
	 * 
	 * @param string $message
	 */
	protected function _send($message)
	{
		$message = $message;
		$success = socket_write($this->socket, $message, strlen($message));
		if (!$success) {
			echo 'Error, could not send message';
			socket_close($this->socket);
		}
	}

	/**
	 * Validate connection
	 * 
	 * @param string $data Data to validate with
	 */
	public function connect($data)
	{
		$this->connected = true;
	}

	/**
	 * Check if connection has been validated
	 * 
	 * @return boolean
	 */
	public function isConnected()
	{
		return $this->connected;
	}

	/**
	 * Get the client session data
	 * 
	 * @return mixed
	 */
	public function getSession()
	{
		return $this->session;
	}

	/**
	 * Set the client session data
	 *
	 * @param mixed $session Data to set
	 * @return A_Socket_Client_Abstract
	 */
	public function setSession($session)
	{
		$this->session = $session;
		return $this;
	}
}