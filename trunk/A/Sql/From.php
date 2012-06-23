<?php
/**
 * From.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Sql_From
 *
 * Generate SQL from tables/joins list
 *
 * @package A_Sql
 */
class A_Sql_From
{

	protected $table = '';
	protected $joins = array();
	protected $current_join = null;

	/**
	 * Constructor
	 *
	 * Casts arguments into tables array
	 *
	 * @param mixed $args
	 */
	public function __construct($args=null)
	{
		$this->table($args);
	}

	/**
	 * Set main table
	 *
	 * @param array $table
	 * @return array
	 */
	public function table($table='')
	{
		if ($table) {
			if (is_array($table)) {
				$this->table = array_shift($table);
				if (count($table) > 0) {
					foreach ($table as $join_table) {
						$this->join($join_table, $this->table, 'INNER');
					}
				}
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
	public function getTables()
	{
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
	 * Create a new join object with provided parameters
	 *
	 * @param array $table_right
	 * @param array $table_left
	 * @param string $type
	 * @return $this
	 */
	public function join($table_right, $table_left=null, $type=null)
	{
		if (is_object($table_right)) {
			$this->current_join = $table_right;
			$this->joins[] = $this->current_join;
		} else {
			if ($type === null) {
				// 2nd param is join type
				if (in_array($table_left, array('INNER', 'OUTER', 'LEFT', 'RIGHT', 'NATURAL', 'CROSS', 'LEFT OUTER', 'RIGHT OUTER', 'FULL OUTER', ))) {
					$type = $table_left;
					$table_left = $this->table;
				}
			}
			// no 2nd param so use base table
			if ($table_left === null) {
				$table_left = $this->table;
			}
			$this->current_join = new A_Sql_Join($table_right, $table_left, $type);
			$this->joins[] = $this->current_join;
		}
		return $this;
	}

	/**
	 * Proxy call to current join object
	 *
	 * @param string $argument1
	 * @param string $argument2
	 * @param string $argument3
	 * @return $this
	 */
	public function on($argument1, $argument2=null, $argument3=null)
	{
		if ($this->current_join) {
			$this->current_join->on($argument1, $argument2, $argument3);
		}
		return $this;
	}

	public function using()
	{
		return $this;
	}

	public function getJoins()
	{
		return $this->joins;
	}

	/**
	 * Automagically render when used in a string context
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Return prepared statement
	 *
	 * @return string
	 */
	public function render()
	{
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
