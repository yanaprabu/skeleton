<?php
/**
 * Base.php
 *
 * @package  A_Db
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Jonah <jonah@nucleussystems.com>, Christopher <christopherxthompson@gmail.com>
 */

/**
 * A_Db_Recordset_Pdo
 * 
 * Database result set for Pdo select, show, or desc queries
 */
class A_Db_Recordset_Pdo extends A_Db_Recordset_Base
{

	/**
	 * Fetches a row as an associative array from database
	 * 
	 * @return array
	 */
	protected function _fetch()
	{
		return $this->result->fetch();
	}
	
	/**
	 * Returns the number of columns in a row
	 * 
	 * @return int
	 */
	public function numCols()
	{
		if ($this->result) {
			return $this->result->columnCount();
		} else {
			return 0;
		}
	}
	
	/**
	 * __call
	 * 
	 * Magic function __call, redirects to instance of Pdo_Result
	 * 
	 * @param string $function Property to access
	 */
	public function __get($name) {
		return $this->result->$name;
	}

	/**
	 * __call
	 * 
	 * Magic function __call, redirects to instance of Pdo_Result
	 * 
	 * @param string $function Function to call
	 * @param array $args Arguments to pass to $function
	 */
	function __call($function, $args)
	{
		return call_user_func_array(array($this->result, $function), $args);
	}
}
