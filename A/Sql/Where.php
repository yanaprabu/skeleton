<?php
/**
 * Where.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Sql_Where
 *
 * Generate SQL WHERE clause
 *
 * @package A_Sql
 */
class A_Sql_Where extends A_Sql_Logicallist
{

	public function render()
	{
		$where = parent::render();
		if ($where) {
			return ' WHERE '. $where;
		}
	}

}
