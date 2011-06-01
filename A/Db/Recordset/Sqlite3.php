<?php
/**
 * Sqlite3.php
 *
 * @package  A_Db
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Jonah <jonah@nucleussystems.com>, Christopher <christopherxthompson@gmail.com>
 */

/**
 * A_Db_Recordset_Sqlite3
 * 
 * Database result set for Sqlite3 select, show, or desc queries
 */
class A_Db_Recordset_Sqlite3 extends A_Db_Recordset_Base
{
	
	/**
	 * Fetches a row as an associative array from database
	 */
	protected function _fetch() {
		return sqlite3_fetch_array($this->result, SQLITE3_ASSOC);
	}
		
	/*
	 * Returns the number of rows in the recordset 
	 */
	public function numRows() {
		if ($this->result) {
			return sqlite3_num_rows($this->result);
		} else {
			return 0;
		}
	}
		
	/*
	 * Returns the number of columns in a row 
	 */
	public function numCols() {
		if ($this->result) {
			return sqlite3_num_cols($this->result);
		} else {
			return 0;
		}
	}
	
}