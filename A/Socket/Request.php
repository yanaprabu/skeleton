<?php

/**
 * This class encapsulates a request from a Socket client
 */
class A_Socket_Request
{

	protected $_method = false;
	protected $_data;
	protected $_route;
	
	public function __construct($data)
	{
		$this->_method = 'GET';
		$this->_data = $data;
		$this->_route = $data->getRoute();
	}
	
	public function get($index)
	{
		if (is_array($this->_route) && isset($this->_route[$index])) {
			return $this->_route[$index];
		} elseif ($index == 'REQUEST_METHOD') {
			return 'GET';
		}
		return false;
	}
	
	public function getData()
	{
		return $this->_data;
	}
}
