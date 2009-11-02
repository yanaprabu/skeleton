<?php

/**
 * 
 * @package A_Orm
 */
class A_Orm_DataMapper_Mapping	{

	public $getMethod;
	public $setMethod;
	public $property;
	public $column;
	public $alias;
	public $table;
	public $callback = array();
	public $param = false;
	public $key = false;

	public function __construct ($getMethod='', $setMethod='', $property='', $column='', $table='', $key = false, $callback = array(), $param = false)	{
		$this->getMethod = $getMethod;
		$this->setMethod = $setMethod;
		$this->property = $property;
		if (is_array ($column))	{
			$this->column = current($column);
			$this->alias = key($column);
		} else	{
			$this->column = $column;
		}
		$this->table = $table;
		$this->key = $key ? true : false;
		$this->callback = $callback;
		$this->param = $param ? true : false;
	}

	public function getSetMethod()	{
		return $this->setMethod;
	}

	public function setSetMethod ($setMethod)	{
		$this->setMethod = $setMethod;
		return $this;
	}

	public function getGetMethod()	{
		return $this->getMethod;
	}

	public function setGetMethod ($getMethod)	{
		$this->getMethod = $getMethod;
		return $this;
	}

	public function getProperty()	{
		return $this->property;
	}

	public function setProperty($property)	{
		$this->property = $property;
		return $this;
	}

	public function getColumn()	{
		return $this->column;
	}

	public function setColumn($column)	{
		$this->column = $column;
		return $this;
	}

	public function setAlias($alias)	{
		$this->alias = $alias;
	}
	
	public function getAlias()	{
		return $this->alias;
	}

	public function getTable()	{
		return $this->table;
	}

	public function setTable($table)	{
		$this->table = $table;
	}
	
	public function isParam()	{
		return $this->param ? true : false;
	}
	
	public function toColumn($column, $table = '', $key = false)	{
		if (is_array ($column))	{
			$this->column = current ($column);
			$this->alias = key ($column);
		} else	{
			$this->column = $column;
		}
		if (strpos($this->column, '.') && empty($table))	{
			list($this->table, $this->column) = explode('.',$this->column);
		} elseif (!empty($table))	{
			$this->table = $table;	
		}
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

	public function isKey()	{
		return $this->key?true:false;
	}

	public function setKey()	{
		$this->key = true;
		return $this;
	}

	public function saveMethod($method)	{
		$this->setGetMethod($method);
		return $this;
	}
	
	public function loadObject($object, $array)	{
		if (method_exists($object, $this->setMethod))	{
			if ($this->property)	{
				$params[] = $this->property;
			}
			$params[] = $this->getValue($array);
			call_user_func_array (array ($object, $this->setMethod), $params);
		} elseif (!empty($this->property) && property_exists($object, $this->property)) {
			$object->{$this->property} = $this->getValue($array);
		}
	}

	public function getValue($array)	{
		if($this->alias)	{
			return $array[$this->alias];
		}elseif ($this->column)	{
			return $array[$this->column];
		}elseif ($this->callback)	{
			call_user_func_array (array ($this->callback['object'], $this->callback['method']), $this->callback['params']);
		}
	}

	public function getValueFromObject($object)	{
		if (method_exists($object, $this->getMethod))	{
			if ($this->property)	{
				$params[] = $this->property;
			}
			$value = call_user_func_array(array($object, $this->getMethod), $params);
		} elseif (property_exists($object, $this->property))	{
			$value = $object->{$this->property};
		}
		return $array;
	}


}