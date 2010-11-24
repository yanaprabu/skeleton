<?php

/**
 * This class encapsulates a request from a WebSocket client
 */
class A_WebSocket_Request
{
	
	public $data = array();
	protected $method = false;
	
	public function __construct($data)
	{
		$this->method = 'GET';
		$this->data = json_decode($data);
	}
	
	public function get($index)
	{
		if (isset($data[$index])) {
			return $data[$index];
		}
		return false;
	}
}