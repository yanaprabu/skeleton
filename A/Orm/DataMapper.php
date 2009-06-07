<?php

class A_Orm_DataMapper	{

	protected $mappings = array();
	protected $class;
	protected $table;

	public function load($array)	{
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
		$mapping = new A_Orm_Mapping();
		$mapping->setSetMethod ($setMethod);
		$mapping->setGetMethod ($getMethod);
		$this->mappings[] = $mapping;
		return $mapping;
	}

	public function mapProperty($property)	{
		$mapping = new A_Orm_Mapping();
		$mapping->setProperty ($property);
		$this->mappings[] = $mapping;
		return $mapping;
	}

	public function setClass($class)	{
		$this->class = $class;
	}

	public function setTable($table)	{
		$this->table = $table;
	}

}