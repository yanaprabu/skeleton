<?php

class A_Socket_Message_Json extends A_Socket_Message
{
	
	public function __construct($message, $client, $clients)
	{
		parent::__construct(json_decode($message), $client, $clients);
	}
	
	public function reply($data, $recipient = self::SENDER)
	{
		$this->_reply(json_encode($data), $recipient);
		return $this;
	}
}
