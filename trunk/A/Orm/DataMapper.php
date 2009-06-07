<?php

class A_Orm_DataMapper	{

	protected $class;

	public function load($array)	{
		if (class_exists ($this->class))	{
			$object = new $this->class();
		} else {
			throw new Exception ('class ' . $this->class . ' does not exist.');
		}
		return $object;
	}

	public function mapMethods($setMethod, $getMethod)	{
		$mapping = new A_Orm_Mapping();
		$mapping->setSetMethod ($setMethod);
		$mapping->setGetMethod ($getMethod);
		$this->map[] = $mapping;
		return $mapping;
	}

	public function mapProperty($property)	{
		$mapping = new A_Orm_Mapping();
		$mapping->setProperty ($property);
		$this->map[] = $mapping;
		return $mapping;
	}

	public function setClass($class)	{
		$this->class = $class;
	}

}