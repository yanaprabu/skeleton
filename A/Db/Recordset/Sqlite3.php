<?php
/**
 * Sqlite3.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	Jonah <jonah@nucleussystems.com>, Christopher <christopherxthompson@gmail.com>
 */

/**
 * A_Db_Recordset_Sqlite3
 * 
 * Database result set for Sqlite3 select, show, or desc queries
 * 
 * @package A_Db
 */
class A_Db_Recordset_Sqlite3 extends A_Db_Recordset_Base
{

	/**
	 * Fetches a row as an associative array from database
	 * 
	 * @return array
	 */
	protected function _fetch()
	{
		return $this->result->fetchArray(SQLITE3_ASSOC);
	}
		
	/**
	 * Returns the number of rows in the recordset 
	 * 
	 * @return int
	 */
	public function numRows()
	{
		if ($this->result) {
			return $this->numRows();
		} else {
			return 0;
		}
	}
		
	/**
	 * Returns the number of columns in a row
	 * 
	 * @return int
	 */
	public function numCols()
	{
		if ($this->result) {
			return $this->result->numColumns();
		} else {
			return 0;
		}
	}

}
