<?php

class A_Orm_DataMapper	{

	function mapMethods($setMethod, $getMethod)	{
		$mapping = new A_Orm_Mapping();
		$mapping->setSetMethod ($setMethod);
		$mapping->setGetMethod ($getMethod);
		return $mapping;
	}

	function mapProperty ($property)	{
		$mapping = new A_Orm_Mapping();
		$mapping->setProperty ($property);
		return $mapping;
	}

}

class A_Orm_Mapping	{

	public $getMethod;
	public setMethod;
	public $property;
	public $field;
	public $alias;
	public $table;
	public $key = false;

	public function __construct ($params = array())	{
		if ($params['getMethod']) $this->getMethod = $params['getMethod'];
		if ($params['setMethod']) $this->getMethod = $params['setMethod'];
		if ($params['property']) $this->getMethod = $params['property'];
		if ($params['field']) $this->getMethod = $params['field'];
		if (!is_numeric (key ($params['field']))) $this->getMethod = key ($params['field']);
		if ($params['table']) $this->getMethod = $params['getMethod'];
		if ($params['key']) $this->getMethod = $params['key'] ? true : false;
	}

	public function setSetMethod ($setMethod)	{
		$this->setMethod = $setMethod;
	}

	public function setGetMethod ($getMethod)	{
		$this->getMethod = $getMethod;
	}

	public function setProperty ($property)	{
		$this->property = $property;
	}

	public function toField ($field = '', $table = '', $key = false)	{
		$this->field = $field;
		if (!is_numeric (key ($field))) = key ($field);
		$this->table = $table;
		$this->key = $key;
	}

}