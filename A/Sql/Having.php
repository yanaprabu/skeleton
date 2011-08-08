<?php
/**
 * Having.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Sql_Having
 * 
 * Generate SQL HAVING clause
 * 
 * @package A_Sql
 */
class A_Sql_Having extends A_Sql_Logicallist
{

	public function render()
	{
		if ($this->data) {
			return ' HAVING '. parent::render();
		}
	}

}
