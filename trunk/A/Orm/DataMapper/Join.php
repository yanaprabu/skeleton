<?php
/**
 * Join.php
 *
 * @package  A_Orm
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Cory Kaufman
 */

/**
 * A_Orm_DataMapper_Join
 */
class A_Orm_DataMapper_Join	{

	public $type;
	public $table;
	public $on;
	public $sql;
	
	public function __construct($table, $on='', $type='INNER')	{
		$this->table = $table;
		$this->on = $on;
		$this->type = $type;
	}

	public function on()	{
		if (func_num_args() == 1)	{
			$this->on = func_get_arg(0);
		} elseif (func_num_args() == 2)	{
			$this->on = $this->table . '.' . func_get_arg(0) . ' = ' . $this->on . '.' . func_get_arg(1);
		}
	}
	
	public function generateSQL()	{
		return $type . ' JOIN ' . $this->table  . ' ON ' . $this->on;
	}

	public function __toString()	{
		return $this->generateSQL();
	}
	
}