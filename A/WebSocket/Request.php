<?php

/**
 * This class encapsulates a request from a WebSocket client
 */
class A_WebSocket_Request
{
	
	public $data = array();
	protected $method = false;
	protected $client;
	
	public function __construct($data, $server, $client)
	{
		$this->method = 'GET';
		$this->data = json_decode($data);
		$this->server = $server;
		$this->client = $client;
	}
	
	public function get($index)
	{
		if (isset($data[$index])) {
			return $data[$index];
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
}