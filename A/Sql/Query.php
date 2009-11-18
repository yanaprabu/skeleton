<?php

/**
 * Lazy load A_Sql_* classes
 * 
 * @package A_Sql 
 */

include_once('A/Sql/Select.php');
include_once('A/Sql/Insert.php');
include_once('A/Sql/Update.php');
include_once('A/Sql/Delete.php');

class A_Sql_Query	{
	
	public function select()	{
		return new A_Sql_Select();
	}
	
	public function insert($table = null, $bind = array())	{
		return new A_Sql_Insert($table, $bind);
	}
	
	public function update($table = null, $bind = array(), $where = array())	{
		return new A_Sql_Update($table, $bind, $where);
	}
	
	public function delete($table = null, $where = array())	{
		return new A_Sql_Delete($table, $where);
	}
	
}