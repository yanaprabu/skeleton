<?php
#require_once 'A/Sql/LogicalList.php';
/**
 * Generate SQL HAVING clause
 * 
 * @package A_Sql 
 */

class A_Sql_Having extends A_Sql_LogicalList {
	public function render() {
		return 'HAVING '. parent::render();
	}
}