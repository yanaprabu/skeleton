<?php

/**
 * This class encapsulates a request from a Socket client
 */
class A_Socket_Request
{

	protected $method = false;
	protected $data;
	
	public function __construct($data)
	{
		$this->method = 'GET';
		$this->data = $data;
	}
	
	public function get($index)
	{
		$message = $this->data->getMessage();
		if (isset($message->type->$index)) {
			return $message->type->$index;
		} elseif ($index == 'REQUEST_METHOD') {
			return 'GET';
		}
		return false;
	}
	
	public function getData()
	{
		return $this->data;
	}
}
