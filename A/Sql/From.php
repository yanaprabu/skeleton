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
	public function render() {
		return 'FROM '. parent::render();		
	}
}
