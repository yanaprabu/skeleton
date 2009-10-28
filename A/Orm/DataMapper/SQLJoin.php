<?php

/**
 * 
 * @package A_Orm
 */
class A_Orm_DataMapper_SQLJoin	{

	public $type;
	public $table1;
	public $table2;
	public $column1;
	public $column2;
	public $sql;
	
	public function __construct($table, $sql = '') {
		$this->table = $table;
		$this->sql = $sql;
	}
	
	public function with($sql)	{
		$this->sql = $sql;
	}
	
	public function generateSql()	{
		return $this->sql;
	}
		
	public function __toString()	{
		return $this->generateSQL();
	}
	
}