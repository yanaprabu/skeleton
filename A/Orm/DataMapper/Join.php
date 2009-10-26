<?php

/**
 * 
 * @package A_Orm
 */
class A_Orm_DataMapper_Join	{

	public $table1;
	public $table2;
	public $column1;
	public $column2;
	
	public function __construct($table1, $table2, $type='inner')	{
		$this->table1 = $table1;
		$this->table2 = $table2;
		$type = $type;
	}

	public function on($column1, $column2)	{
		$this->column1 = $column1;
		$this->column2 = $column2;
	}

}