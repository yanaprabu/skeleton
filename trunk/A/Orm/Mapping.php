<?php

class A_Orm_Mapping	{

	public $getMethod;
	public $setMethod;
	public $property;
	public $field;
	public $alias;
	public $table;
	public $key = false;

	public function __construct ($params = array())	{
		if ($params['getMethod']) $this->getMethod = $params['getMethod'];
		if ($params['setMethod']) $this->setMethod = $params['setMethod'];
		if ($params['property']) $this->property = $params['property'];
		if (is_array ($params['field']))	{
			$this->field = current($params['field']);
			$this->alias = key ($params['field']);
		} else	{
			$this->field = $params['field'];
		}
		if ($params['table']) $this->table = $params['table'];
		if ($params['key']) $this->key = $params['key'] ? true : false;
	}

	public function getSetMethod()	{
		return $this->setMethod;
	}

	public function setSetMethod ($setMethod)	{
		$this->setMethod = $setMethod;
	}

	public function getGetMethod()	{
		return $this->getMethod;
	}

	public function setGetMethod ($getMethod)	{
		$this->getMethod = $getMethod;
	}

	public function getProperty()	{
		return $this->property;
	}

	public function setProperty($property)	{
		$this->property = $property;
	}

	public function getField()	{
		return $this->field;
	}

	public function setField($field)	{
		$this->field = $field;
	}

	public function getAlias()	{
		return $this->alias;
	}

	public function getTable()	{
		return $this->table;
	}

	public function isKey()	{
		return $this->key?true:false;
	}

	public function toField($field = '', $table = '', $key = false)	{
		if (is_array ($field))	{
			$this->field = current ($field);
			$this->alias = key ($field);
		} else	{
			$this->field = $field;
		}
		$this->table = $table;
		$this->key = $key;
	}

	public function map($object)	{
		if ($this->getMethod) $object->{$this->getMethod}($this->field);
	}

}