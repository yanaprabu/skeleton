<?php

class A_Sql_Join_String	{
	
	public function __construct($sql)	{
		$this->sql = $sql;
	}
	
	public function render()	{
		return $this->sql;
	}
	
	public function __toString()	{
		return $this->render();
	}
	
}