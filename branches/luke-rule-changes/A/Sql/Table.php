<?php
require_once 'A/Sql/Columns.php';
/**
 * Generate SQL table string
 * 
 * @package A_Sql 
 */

class A_Sql_Table extends A_Sql_Columns  {
	/** 
	 * Alias for getColumns()
	*/
	public function getTables() {
		return $this->getColumns();
	}
}
