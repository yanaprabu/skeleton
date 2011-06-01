<?php
/**
 * Table.php
 *
 * @package  A_Sql
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Sql_Table
 * 
 * Generate SQL table columns list
 */
class A_Sql_Columns {
	protected $table = '';
	protected $joins = array();

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
			if (strpos($columns, ',')) { //if user passed string of multiple columns, we need to account those ose
				unset($this->columns[$key]);
				$this->columns = array_merge($this->columns, explode(',', $columns));
			} 
		}
		$this->columns = array_filter(array_map('trim', (array)$this->columns));
	}
	
	/**
	 * Return list of columns
	 *
	 * @return array
	 */	
	public function getTables() {
		if ($this->joins) {
			return array_merge(array($this->table), array_keys($this->joins));
		} else {
			return array($this->table);
		}
	}

	public function join()	{
		if (func_num_args()) {
			$join = A_Sql_Join(func_get_arg(0),func_get_arg(1),func_get_arg(2));
			$this->joins[$join->getTable()] = $join;
		}
		return $this;
	}
	
	public function getJoins()	{
		return $this->joins();
	}
	
	/**
	 * Magic string transformation
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->render();
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
}
