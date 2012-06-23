<?php
/**
 * Join.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Db_Datamapper_Join
 *
 * @package A_Db
 */
class A_Db_Datamapper_Join
{

	public $table1;
	public $field1;
	public $table2;
	public $field2;
	public $type;

	function __construct($table1, $field1, $table2, $field2, $type='')
	{
		$this->table1 = $table1;
		$this->field1 = $field1;
		$this->table2 = $table2;
		$this->field2 = $field2;
		$this->type = $type;
	}

	function render()
	{
		return " {$this->table1} {$this->type} JOIN {$this->table2} ON {$this->table1}.{$this->field1}={$this->table2}.{$this->field2} ";
	}

}
