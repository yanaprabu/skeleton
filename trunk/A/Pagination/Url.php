<?php

class A_Pagination_Url	{

	protected $base = '';
	protected $state = array();

	public function __construct ($base)	{
		$this->base = $base;
	}

	public function set ($key, $value)	{
		$this->state[$key] = $value;
	}

	public function build ($params = array())	{
		return $this->base . '?' . http_build_query (array_merge ($params, $this->state));
	}

}