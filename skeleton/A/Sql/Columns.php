<?php
/**
 * A_Sql_Columns
 *
 * @license    BSD
 * @version    $Id:$
 */
class A_Sql_Columns {
	/**
	 * Columns array
	 * @var array
	 */
	protected $columns = array();

	/**
	 * Class constructor
	 * 
	 * Casts arguments into columns array
	 *
	 * @param mixed $args
	 */
	public function __construct($args=null) {
		if (is_array($args)) {
			if (isset($args[0]) && is_array($args[0])) {
				$this->columns = $args[0];
			} else {
				$this->columns = $args;
			}
		} else {
			$this->columns = func_get_args();
		}
	}
	
	/**
	 * Return prepared statement
	 *
	 * @return string
	 * @question Why does this method also sets the columns member? {pytrin}
	 */
	public function render() {
		if ($this->columns) {
			if (is_array($this->columns) && count($this->columns)) {
				$this->columns = implode(', ', $this->columns);
			}
			return $this->columns;
		}
	}

	/**
	 * Perform table join
	 *
	 * @param string $table1
	 * @param string $column1
	 * @param string $table2
	 * @param string $column2
	 * @return self
	 */
	public function join($table1, $column1, $table2, $column2) {
		include_once('A/Sql/Join.php');
		$this->joins[$table2] = new A_Sql_Join($table1, $column1, $table2, $column2);
		return $this;
	}

	/**
	 * Magic string transformation
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->render();
	}
}
