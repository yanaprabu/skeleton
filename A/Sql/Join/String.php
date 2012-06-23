<?php
/**
 * String.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Sql_Join_Strings
 *
 * @package A_Sql
 */
class A_Sql_Join_String
{

	public function __construct($sql)
	{
		$this->sql = $sql;
	}

	public function render()
	{
		return $this->sql;
	}

	public function __toString()
	{
		return $this->render();
	}

}
