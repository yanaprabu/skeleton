<?php
/**
 * Database result set for MySQL select, show, or desc queries
 * 
 * @package A_Db_Recordset
 * @author Jonah Dahlquist <jonah@nucleussystems.com>
 */
class A_Db_Recordset_MySQL extends A_Db_Recordset_Base
{

	/**
	 * Fetches a row as an associative array from database
	 */
	protected function _fetch() {
		return mysql_fetch_assoc($this->result);
	}
		
	/*
	 * Returns the number of rows in the recordset 
	 */
	public function numRows() {
		if ($this->result) {
			return mysql_num_rows($this->result);
		} else {
			return 0;
		}
	}
		
	/*
	 * Returns the number of columns in a row 
	 */
	public function numCols() {
		if ($this->result) {
			return mysql_num_cols($this->result);
		} else {
			return 0;
		}
	}
	
}