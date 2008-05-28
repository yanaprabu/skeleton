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
		
		foreach ((array)$this->columns as $key => $columns) {
			if (strpos($columns, ',')) {
				unset($this->columns[$key]);
				$this->columns = array_merge($this->columns, explode(',', $columns));
			} 
		}
		$this->columns = array_filter(array_map('trim', (array)$this->columns));
	}
	
	public function getColumns() {
		return $this->columns;
	}
	
	/**
	 * Return prepared statement
	 *
	 * @return string
	 */
	public function render() {
		if (!count($this->columns)) {
			return '';
		}
		return implode(', ', $this->columns);
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
