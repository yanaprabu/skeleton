<?php
/**
 * Orderby.php
 *
 * @package  A_Sql
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Sql_Orderby
 * 
 * Generate SQL ORDER BY clause
 */
class A_Sql_Orderby extends A_Sql_Columns
{

	public function render()
	{
		return ' ORDER BY '. parent::render();
	}

}
