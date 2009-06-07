<?php

class A_Orm_DataMapper	{

	protected $mappings = array();
	protected $class;
	protected $table;

	public function __construct($db, $class, $table='') {
	     $this->db = $db;
	     $this->class = $class;
	     $this->table = $table;
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

	public function loadOne($array)	{
		if (!class_exists ($this->class))	{
			throw new Exception ('class ' . $this->class . ' does not exist.');
		}
		$object = new $this->class();
		foreach ($this->mappings as $mapping)	{
			$mapping->map ($object, $array);
		}
		return $object;
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
		$mappig->setGeneric($name);
		return $mapping;
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
		foreach ($this->mappings as $mapping)	{
			if ($mapping->getAlias())	{
				$fields[] = array ($mapping->getAlias() => $mapping->getField());
			} else 	{
				$fields[] = $mapping->getField();
			}
		}
		return $fields;
	}

}