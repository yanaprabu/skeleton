<?php

class A_Socket_Message
{

	const SENDER = 0;
	const ALL = 1;
	const OTHERS = 2;
	
	private $message;
	private $client;
	private $clients;
	
	public function __construct($message, $client, $clients)
	{
		$this->message = $message;
		$this->client = $client;
		$this->clients = $clients;
	}
	
	public function getMessage()
	{
		return $this->message;
	}
	
	public function reply($data, $recipient = self::SENDER)
	{
		$this->_reply($data, $recipient);
		return $this;
	}
	
	public function getSession()
	{
		return $this->client->getSession();
	}
	
	public function setSession($session)
	{
		$this->client->setSession($session);
		return $this;
	}
	
	public function getAllSessions()
	{
		$sessions = array();
		foreach ($this->clients as $client) {
			$sessions[] = $client->getSession();
		}
		return $sessions;
	}
	
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
