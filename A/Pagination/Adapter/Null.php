<?php

class A_Pagination_Adapter_Null implements A_Pagination_Adapter_Interface	{

	protected $numItems = 0;

	public function __construct($numItems)	{
		$this->numItems = $numItems;
	}

	public function getNumItems()	{
		return $this->numItems;
	}

	public function getItems()	{
		return array();
	}

	public function setOrderBy($offset, $length)	{
		return null;
	}

}