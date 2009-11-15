<?php

/**
 * 
 * @package A_Orm
 */
class A_Orm_DataMapper_SQLJoin	{
	
	public function __construct($sql) {
		$this->sql = $sql;
	}
	
	public function with($sql)	{
		$this->sql = $sql;
	}
	
	public function getTable()	{
		preg_match('/JOIN ([^\n]*) ON/i', $this->sql, $matches);
		return $matches[1];
	}
	
	public function render()	{
		return $this->sql;
	}
		
	public function __toString()	{
		return $this->render();
	}
	
}