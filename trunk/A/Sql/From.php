<?php
require_once 'A/Sql/Table.php';
/**
 * Generate SQL FROM clause
 * 
 * @package A_Sql 
 */

class A_Sql_From extends A_Sql_Table  {
	/**
	 * Return prepared statement
	 *
	 * @return string
	 */
	
	public function addJoin($join)	{
		$this->joins[] = $join;
		return $join;
	}
	
	public function join()	{
		if(func_num_args() == 1)	{
			$join = new A_Sql_Join_String(func_get_arg(0));
		} else	{
			$join = new A_Sql_Join_Parameters(func_get_arg(0),func_get_arg(1),func_get_arg(2));	
		}
		return $this->addJoin($join);
	}
	
	public function getJoins()	{
		return $this->joins();
	}
	
	public function render() {
		return 'FROM '. parent::render();		
	}
	
}
