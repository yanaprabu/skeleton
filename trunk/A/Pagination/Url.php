<?php

class A_Pagination_Url	{

	protected $base;
	protected $protocol;
	protected $state = array();

	public function __construct ($base, $protocol = 'http')	{
		$this->base = $base;
		$this->protocol = $protocol;
	}

	public function set ($key, $value)	{
		$this->state[$key] = $value;
	}

	public function setBase ($base)	{
		$this->base = $base;
	}

	public function setProtocol ($protocol)	{
		$this->protocol = $protocol;
	}

	public function render ($page = false, $params = array())	{
		$params = array_merge ($params, $this->state);
		$base = $this->base ? $this->protocol . '://' . $this->base . '/' : '';
		$page = $page ? $page : $_SERVER['SCRIPT_NAME'];
		$query = count ($params) > 0 ? '?' . http_build_query ($params) : '';
		return $base . $page . $query;
	}

}