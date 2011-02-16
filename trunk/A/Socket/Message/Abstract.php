<?php

/**
 * Message object, created by server to hold the data from a connected client.
 */
abstract class A_Socket_Message_Abstract
{

	// Only reply to sender
	const SENDER = 0;
	// Reply to all clients
	const ALL = 1;
	// Reply to all but the sender
	const OTHERS = 2;

	/**
	 * The raw message data
	 * @var mixed
	 */
	protected $message;
	
	/**
	 * The sender client
	 * @var object
	 */
	protected $client;

	/**
	 * An array of all clients connected
	 * @var array
	 */
	protected $clients;

	/**
	 * Constructor
	 *
	 * @param mixed $message Raw message data
	 * @param object $client Sender client
	 * @param array $clients All clients
	 */
	public function __construct($message, $client, $clients)
	{
		$this->message = $message;
		$this->client = $client;
		$this->clients = $clients;
	}

	/**
	 * Send message back to client(s)
	 *
	 * @param mixed $data Message to send
	 * @param integer $recipient Set of clients to reply to
	 */
	abstract public function reply($data, $recipient = self::SENDER);

	/**
	 * Get route data from message
	 */
	abstract public function getRoute();

	/**
	 * Get the actual message data
	 * 
	 * @return mixed
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * Get the session from the sender client
	 *
	 * @return mixed
	 */
	public function getSession()
	{
		return $this->client->getSession();
	}

	/**
	 * Set the session of the sender client
	 *
	 * @param mixed $session
	 * @return A_Socket_Message_Abstract
	 */
	public function setSession($session)
	{
		$this->client->setSession($session);
		return $this;
	}

	/**
	 * Get sessions from all connected clients
	 * 
	 * @return array
	 */
	public function getAllSessions()
	{
		$sessions = array();
		foreach ($this->clients as $client) {
			$sessions[] = $client->getSession();
		}
		return $sessions;
	}

	/**
	 * Send message back to client(s)
	 *
	 * @param mixed $data Data to send
	 * @param mixed $recipient Set of clients to respond to
	 * @return A_Socket_Message_Abstract
	 */
	protected function _reply($data, $recipient)
	{
		if ($recipient == self::SENDER) {
			$this->client->send($data);
			
		} elseif ($recipient == self::ALL) {
			foreach ($this->clients as $client) {
				$client->send($data);
			}
			
		} elseif ($recipient == self::OTHERS) {
			foreach ($this->clients as $client) {
				if ($client != $this->client) {
					$client->send($data);
				}
			}
			
		} elseif (is_callable($recipient)) {
			foreach ($this->clients as $client) {
				if (call_user_func($recipient, $client->getSession())) {
					$client->send($data);
				}
			}
		}
		return $this;
	}
}
