<?php

/**
 * This class encapsulates a request from a WebSocket client
 */
class A_WebSocket_Request
{
	
	protected $type;
	protected $message;
	protected $method = false;
	protected $client;
	
	public function __construct($data, $server, $client)
	{
		$this->method = 'GET';
		$data = json_decode($data);
		$this->type = $data->type;
		$this->message = $data->data;
		$this->server = $server;
		$this->client = $client;
	}
	
	public function get($index)
	{
		if (isset($this->type->$index)) {
			return $this->type->$index;
		}
		return false;
	}
	
	public function getClient()
	{
		return $this->client;
	}
	
	public function getServer()
	{
		return $this->server;
	}
	
	public function getMessage()
	{
		return $this->message;
	}
}