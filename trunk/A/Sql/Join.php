<?php
/**
 * Join.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Sql_Join
 *
 * Generate SQL joins
 *
 * @package A_Sql
 */
class A_Sql_Join
{

	protected $type = '';
	protected $table_right = '';
	protected $table_left = '';
	protected $on = null;
	protected $on_expression = null;

	public function __construct($table_right=null, $table_left=null, $type=null)
	{
		if ($table_right) {
			$this->join($table_right, $table_left, $type);
		}
	}

	public function join($table_right, $table_left, $type=null)
	{
		$this->type = ($type !== null) ? strtoupper($type) : 'INNER';
		$this->table_right = $table_right;
		$this->on = null;
		// is there a full ON expression in 2nd param
		if (strpos('=', $table_left) === false) {
			$this->on_expression = null;
			$this->table_left = $table_left;
		} else {
			$this->on_expression = $table_left;
			$this->table_left = '';
		}
		return $this;
	}

	public function on($argument1, $argument2=null, $argument3=null)
	{
		if (!$this->table_right) { //no join has been set yet
			return;
		}
		if (!$this->on) {
			$this->on = new A_Sql_Logicallist();
			$this->on->setEscape(false);
		}
		$this->on_expression = null;
		if (is_array($argument1)) {
			if (!count($argument1)) {  //empty array of expressions was passed
				return;
			}
			foreach ($argument1 as $column1 => $column2) {
				// check if is a quoted string rather than a column name
				if (substr($column1, 0, 1) != "'") {
					$column1 = $this->prependTableAlias($this->table_right, $column1);
				}
				// check if is a quoted string rather than a column name
				if (substr($column2, 0, 1) != "'") {
					$column2 = $this->prependTableAlias($this->table_left, $column2);
				}
				$this->on->addExpression($column1, $column2);
			}
		} else {
			//since we allow different style of parameters we must account for different
			//amount of parameters
			if ($argument3 === null && !is_array($argument2)) {
				$logic = 'AND';
			} else {
				$logic = $argument1;
				$argument1 = $argument2;
				$argument2 = $argument3;
			}
			if (substr($argument1, 0, 1) != "'") {
				$argument1 = $this->prependTableAlias($this->table_right, $argument1);
			}
			if (substr($argument2, 0, 1) != "'") {
				$argument2 = $this->prependTableAlias($this->table_left, $argument2);
			}
			$this->on->addExpression($logic, $argument1, $argument2);
		}
		return $this;
	}

	public function render()
	{
		$return = '';
		if ($this->table_right) {
			$on = '';
			// render ON if object or use literal experession
			if ($this->on) {
				$on = ' ON '. $this->on->render();
			} elseif ($this->on_expression) {
				$on = ' ON '. $this->on_expression;
			}
			$type = $this->type ? ' '. $this->type : '';
			$return .= "$type JOIN {$this->table_right}$on";
		}
		return $return;
	}

	protected function prependTableAlias($alias, $table)
	{
		if (!strpos($table, '.')) { //already an alias
			return $alias .'.'. $table;
		}
		return $table;
	}

	public function getTables()
	{
		$tables = array();
		if ($this->table_right) {
			$tables[] = $this->table_right;
			if ($this->table_left) {
				$tables[] = $this->table_left;
			}
		}
		return $tables;
	}

	public function __toString()
	{
		return $this->render();
	}

}
