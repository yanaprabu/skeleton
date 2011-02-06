<?php

class A_Socket_Message_Json extends A_Socket_Message_Abstract
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

	public function getRoute()
	{
		if (isset($this->message->route)) {
			return $this->message->route;
		} elseif (isset($this->message->module, $this->message->controller, $this->message->action)) {
			return array($this->message->module, $this->message->controller, $this->message->action);
		} else {
			return null;
		}
	}
}
