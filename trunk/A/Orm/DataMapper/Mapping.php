<?php

class A_Orm_DataMapper_Mapping	{

	public $getMethod;
	public $setMethod;
	public $property;
	public $genericName;
	public $column;
	public $alias;
	public $table;
	public $callback = array();
	public $key = false;

	public function __construct ($getMethod='', $setMethod='', $property='', $genericName='', $column='', $table='', $key = false, $callback = array())	{
		$this->getMethod = $getMethod;
		$this->setMethod = $setMethod;
		$this->property = $property;
		$this->genericName = $genericName;
		if (is_array ($column))	{
			list ($this->alias, $this->column) = each ($column);
		} else	{
			$this->column = $column;
		}
		$this->table = $table;
		$this->key = $key ? true : false;
		$this->callback = $callback;
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

	public function getGenericName()	{
		return $this->genericName;
	}

	public function setGenericName($name)	{
		$this->genericName = $name;
	}

	public function getColumn()	{
		return $this->column;
	}

	public function setColumn($column)	{
		$this->column = $column;
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

	public function setKey()	{
		$this->key = true;
	}

	public function toColumn($column, $table = '', $key = false)	{
		if (is_array ($column))	{
			$this->column = current ($column);
			$this->alias = key ($column);
		} else	{
			$this->column = $column;
		}
		$this->table = $table;
		$this->key = $key;
		return $this;
	}

	public function toCallback($object, $method, $params = array())	{
		$this->callback = array (
			'object' => $object,
			'method' => $method,
			'params' => params
		);
		return $this;
	}

	public function loadObject($object, $array)	{
		if (method_exists ($object, $this->setMethod))	{
			$value = $this->property ? array ($this->property, $this->getValue($array)) : array($this->getValue($array));
			call_user_func_array (array ($object, $this->setMethod), $value);
		} elseif (property_exists ($object, $this->property))	{
			$object->{$this->property} = $this->getValue($array);
		} elseif (method_exists ($object, 'get') && method_exists ($object, 'set') && $this->genericName)	{
			$object->set ($this->genericName, $this->getValue($array));
		} else	{
			throw new Exception ('could not map');
		}
	}

	public function getValue($array)	{
		if ($this->column)	{
			return $array[$this->column];
		} elseif ($this->callback)	{
			call_user_func_array (array ($this->callback['object'], $this->callback['method']), $this->callback['params']);
		}
	}

}