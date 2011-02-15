<?php

class A_Socket_Message_Json extends A_Socket_Message_Abstract
{
	
	public function __construct($message, $client = null, $clients = array())
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
		} elseif (isset($this->message->type->module, $this->message->type->controller, $this->message->type->action)) {
			return array(
				'module' => $this->message->type->module,
				'controller' => $this->message->type->controller,
				'action' => $this->message->type->action
			);
		} else {
			return null;
		}
	}
}
