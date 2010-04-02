<?php
#require_once 'A/Sql/Columns.php';
/**
 * Generate SQL ORDER BY clause
 * 
 * @package A_Sql 
 */

class A_Sql_Orderby extends A_Sql_Columns {
	public function render() {
		return ' ORDER BY '. parent::render();
	}
}
