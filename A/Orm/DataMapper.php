<?php

class A_Orm_DataMapper	{

	protected $mappings = array();
	protected $params = array();
	protected $class;
	protected $table;

	public function __construct($db='', $class='', $table='') {

	public function __construct($db, $class, $table='', $params=array()) {
	     $this->db = $db;
	     $this->class = $class;
	     $this->table = $table;
	     $this->params = $params;
	}

	public function setDb($db) {
	     $this->db = $db;
		return $this;
	}

	public function setClass($class)	{
		$this->class = $class;
		return $this;
	}

	public function setTable($table)	{
		$this->table = $table;
		return $this;
	}

	public function load($array)	{
		$object = call_user_func_array (array ($this, 'create'), $this->getParams($array));
		foreach ($this->mappings as $mapping)	{
			$mapping->loadObject ($object, $array);
		}
		return $object;
	}

	public function create()	{
		if (!class_exists ($this->class))	{
			throw new Exception ('class ' . $this->class . ' does not exist.');
		}
		$class = new ReflectionClass($this->class);
		return $class->newInstanceArgs(func_get_args());
	}

	public function map($property)	{
		$mapping = new A_Orm_DataMapper_Mapping();
		$this->mappings[] = $mapping;
		$mapping->setProperty ($property);
		return $mapping;
	}

	public function mapMethods($getMethod, $setMethod)	{
		$mapping = new A_Orm_DataMapper_Mapping();
		$this->mappings[] = $mapping;
		$mapping->setSetMethod ($setMethod);
		$mapping->setGetMethod ($getMethod);
		return $mapping;
	}

	public function mapProperty($property)	{
		$mapping = new A_Orm_DataMapper_Mapping();
		$this->mappings[] = $mapping;
		$mapping->setProperty ($property);
		return $mapping;
	}

	public function mapGeneric($name)	{
		$mapping = new A_Orm_DataMapper_Mapping();
		$this->mappings[] = $mapping;
		$mapping->setSetMethod('set');
		$mapping->setGetMethod('get');
		$mapping->setGeneric($name);
		return $mapping;
	}

	public function mapParam()	{
		$param = new A_Orm_DataMapper_Mapping();
		$this->params[] = $param;
		return $param;
	}

	public function getParams($array)	{
		$params = array();
		foreach ($this->params as $param)	{
			$params[] = $param->getValue($array);
		}
		return $params;
	}

	public function getTableNames() {
		$tables = array();
		if ($this->table) $tables[] = $this->table;
		foreach ($this->mappings as $mapping) {
			if ($mapping->getTable() && !in_array ($mapping->getTable(), $tables)) {
				$tables[] = $mapping->getTable();
			}
		}
		return $tables;
	}

	public function getFieldNames()	{
		$fields = array();
		foreach (array_merge ($this->mappings, $this->params) as $mapping)	{
			if ($mapping->getAlias())	{
				$fields[] = array ($mapping->getAlias() => $mapping->getColumn());
			} else 	{
				$fields[] = $mapping->getColumn();
			}
		}
		return $fields;
	}

}