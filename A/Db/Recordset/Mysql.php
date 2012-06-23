<?php
/**
 * Base.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	Jonah <jonah@nucleussystems.com>, Christopher <christopherxthompson@gmail.com>
 */

/**
 * A_Db_Recordset_Mysql
 *
 * Database result set for Mysql select, show, or desc queries
 *
 * @package A_Db
 */
class A_Db_Recordset_Mysql extends A_Db_Recordset_Base
{

	/**
	 * Fetches a row as an associative array from database
	 *
	 * @return array
	 */
	protected function _fetch()
	{
		return mysql_fetch_assoc($this->result);
	}

	/**
	 * Returns the number of rows in the recordset
	 *
	 * @return int
	 */
	public function numRows()
	{
		if ($this->result) {
			return mysql_num_rows($this->result);
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
			return mysql_num_cols($this->result);
		} else {
			return 0;
		}
	}

}
