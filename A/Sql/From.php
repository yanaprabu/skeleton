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
	public function table($table='') {
		if ($table) {
			if (is_array($table)) {
				$this->table = $table[0];
				// add other table names in array as JOINs?
			} else {
				$this->table = $table;
			}
		}
		return $this;
	}

	/**
	 * Return list of tables
	 *
	 * @return array
	 */	
	public function getTables() {
		$tables = array();
		if ($this->table) {
			$tables[$this->table] = true;
		}
		if ($this->joins) {
			foreach ($this->joins as $join) {
				foreach ($join->getTables() as $table) {
					$tables[$table] = true;
				}
			}
		}
		return array_keys($tables);
	}

	/**
	 * Add a new join object manually
	 */
	public function addJoin($join)	{
		$this->current_join = $join;
		$this->joins[] = $join;
		return $join;
	}
	
	/**
	 * Create a new join object with provided parameters
	 */
	public function join($table1, $table2=null, $type=null)	{
		if ($table1) {
			if ($type === null) {
				// 2nd param is join type
				if (in_array($table2, array('INNER', 'OUTER', 'LEFT', 'RIGHT', 'NATURAL', 'CROSS', 'LEFT OUTER', 'RIGHT OUTER', 'FULL OUTER', ))) {
					$type = $table2;
					$table2 = $this->table;
				}
			}
			// no 2nd param so use base table
			if ($table2 === null) {
				$table2 = $this->table;
			}
			#require_once 'A/Sql/Join.php';
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
			$this->sql .= $join->render();
		}
		return $this->sql;		
	}
}
