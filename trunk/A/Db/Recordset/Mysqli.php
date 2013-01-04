<?php
/**
 * Base.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	Jonah <jonah@nucleussystems.com>, Christopher <christopherxthompson@gmail.com>
 */

/**
 * A_Db_Recordset_Mysqli
 *
 * Database result set for Mysqli select, show, or desc queries
 *
 * @package A_Db
 */
class A_Db_Recordset_Mysqli extends A_Db_Recordset_Base
{

	/**
	 * Fetches a row as an associative array from database
	 *
	 * @return array
	 */
	protected function _fetch()
	{
		return $this->result->fetch_assoc();
	}

	/**
	 * Returns the number of rows in the recordset
	 *
	 * @return int
	 */
	public function numRows()
	{
		if ($this->result) {
			return $this->result->num_rows;
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
			return $this->result->field_count;
		} else {
			return 0;
		}
	}

	/**
	 * __get
	 *
	 * Magic function __get, redirects to instance of Mysqli_Result
	 *
	 * @param string $function Property to access
	 */
	public function __get($name)
	{
		return $this->result->$name;
	}

	/**
	 * __call
	 *
	 * Magic function __call, redirects to instance of Mysqli_Result
	 *
	 * @param string $function Function to call
	 * @param array $args Arguments to pass to $function
	 */
	function __call($function, $args)
	{
		return call_user_func_array(array($this->result, $function), $args);
	}
}
