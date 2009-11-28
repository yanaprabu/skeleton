<?php
/**
 * Generate SQL from tables/joins list
 * 
 * @package A_Sql 
 *
 * @license    BSD
 * @version    $Id:$
 */
class A_Sql_From {
	protected $table = '';
	protected $joins = array();
	protected $current_join = null;
	
	/**
	 * Class constructor
	 * 
	 * Casts arguments into tables array
	 *
	 * @param mixed $args
	 */
	public function __construct($args=null) {
		$this->table($args);
	}
	
	/**
	 * Set main table
	 *
	 * @return array
	 */	
	public function table($args=null) {
		if (is_array($args)) {
			if (isset($args[0]) && is_array($args[0])) {
				$this->tables = $args[0];
			} else {
				$this->table = $args;
			}
		} else {
			$this->table = $args;
		}
		return $this;
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

	/**
	 * Create a new join object with provided parameters
	 */
	public function join($table1, $table2, $type=null)	{
		if ($table1 && $table2) {
			require_once 'A/Sql/Join.php';
			$this->current_join = new A_Sql_Join($table1, $table2, $type);
			$this->joins[] = $this->current_join;
		}
		return $this;
	}
	
	/**
	 * Proxy call to current join object
	 */
	public function on($argument1, $argument2=null, $argument3=null) {
		if ($this->current_join) {
			$this->current_join->on($argument1, $argument2, $argument3);
		}
		return $this;
	}

	public function using()	{
		return $this;
	}

	public function getJoins()	{
		return $this->joins;
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
		if (!$this->table) {
			return '';
		}
		$this->sql = $this->table;
		foreach ($this->joins as $join) {
echo "render join<br/>";
			$this->sql .= $join->render();
		}
		return $this->sql;		
	}
}
