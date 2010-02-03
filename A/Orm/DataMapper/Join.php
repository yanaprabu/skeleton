<?php
/**
 * A_Orm_DataMapper_Join
 *
 * @author Cory Kaufman
 * @package A_Orm
 * @version @package_version@
 */

class A_Orm_DataMapper_Join	{

	public $type;
	public $table1;
	public $table2;
	public $column1;
	public $column2;
	public $sql;
	
	public function __construct($table1, $table2 = '', $type='INNER')	{
		$this->table1 = $table1;
		$this->table2 = $table2;
		$this->type = $type;
	}

	public function on()	{
		if (func_num_args() == 1)	{
			$this->on = func_get_arg(0);
		} elseif (func_num_args() == 2)	{
			$this->on = $this->table1 . '.' . func_get_arg(0) . ' = ' . $this->table2 . '.' . func_get_arg(1);
		}
	}
	
	public function generateSQL()	{
		return $type . ' JOIN ' . $this->table1  . ' ON ' . $this->on;
	}

	public function __toString()	{
		return $this->generateSQL();
	}
	
}