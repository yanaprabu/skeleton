<?php
/**
 * Database result set for MySQL select, show, or desc queries
 * 
 * @package A_Db_Recordset
 * @author Jonah Dahlquist <jonah@nucleussystems.com>
 */
class A_Db_Recordset_MySQL extends A_Db_Recordset_Abstract
{

	/**
	 * Fetches a row as an associative array from database
	 */
	protected function _fetch() {
		return mysql_fetch_assoc($this->result);
	}
		
	public function numRows() {
		if ($this->result) {
			return mysql_num_rows($this->result);
		} else {
			return 0;
		}
	}
		
	public function numCols() {
		if ($this->result) {
			return mysql_num_cols($this->result);
		} else {
			return 0;
		}
	}
	
}