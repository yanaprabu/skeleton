<?php

/**
 * Client
 */
class A_Socket_Client_Abstract
{
	
	private $socket;
	
	private $connected = false;
	
	private $session;
	
	/**
	 * Constructor
	 */
	public function __construct($socket)
	{
		$this->socket = $socket;
	}
	
	public function send($message)
	{
		$this->_send($message);
	}
	
	protected function _send($message)
	{
		$message = $message;
		$success = socket_write($this->socket, $message, strlen($message));
		if (!$success) {
			echo 'Error, could not send message';
			socket_close($this->socket);
		}
	}
	
	public function connect($data)
	{
		$this->connected = true;
	}
	
	public function isConnected()
	{
		return $this->connected;
	}
	
	public function getSession()
	{
		return $this->session;
	}
	
	public function setSession($session)
	{
		$this->session = $session;
		return $this;
	}
}
