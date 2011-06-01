<?php
/**
 * Where.php
 *
 * @package  A_Sql
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Sql_Where
 * 
 * Generate SQL WHERE clause 
 */
class A_Sql_Where extends A_Sql_LogicalList {
	public function render() {
		$where = parent::render();
		if ($where) {
			return ' WHERE '. $where;
		}
	}
}