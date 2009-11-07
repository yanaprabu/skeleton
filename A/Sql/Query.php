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

	protected $select;
	protected $insert;
	protected $update;
	protected $delete;

	public function select()	{
		if(!$this->select instanceof A_Sql_Select)	{
			$this->select = new A_Sql_Select();
		}
		return $this->select;
	}
	
	public function insert($table = null, $bind = array())	{
		if(!$this->insert instanceof A_Sql_Insert)	{
			$this->insert = new A_Sql_Insert($table, $bind);
		} else	{
			if($bind)	{
				$this->columns($bind);
			}
			$this->insert->table($table);
		}
		return $this->insert;
	}
	
	public function update($table = null, $bind = array(), $where = array())	{
		if(!$this->update instanceof A_Sql_Update)	{
			$this->update = new A_Sql_Update($table, $bind, $where);
		} else	{
			if($bind)	{
				$this->update->columns($bind);
			}
			$this->update->table($table)->where($where);
		}
		return $this->update;
	}
	
	public function delete($table = null, $where = array())	{
		if(!$this->delete instanceof A_Sql_Delete)	{
			$this->delete = new A_Sql_Delete($table, $where);
		} else	{
			$this->delete->table($table)->where($where);
		}
		return $this->delete;
	}
	
}