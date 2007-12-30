<?php

include_once 'A/Db/Sql/Common.php';

class A_Db_Sql_Select extends A_Db_Sql_Common {
	protected $db;
	protected $table;
	protected $columns = array();
	protected $where = array();
	protected $joins = array();
	protected $sqlFormat = 'SELECT %s FROM %s %s WHERE %s';
	protected $whereLogic = ' AND ';
	
	public function __construct($db=null) {
		$this->db = $db !== null ? $db : $this;
	}
		
	public function columns() {
		if (func_num_args()) {
			$args = func_get_args();
			if (!array_search('*', $args)) { //if wildcard was passed, ignore it
				$this->columns = is_array($args[0]) ? $args[0] : $args; //if we received array use that instead of arguments
			}
		}
		return $this;
	} 

	public function from($table) {
		$this->table = $table;
		return $this;
	}

	public function join($join) {
		if ($join instanceof A_Db_Sql_Join) {
			$this->joins[] = $join;
		}
		return $this;
	}

	public function where($data, $value=null) {
		if (is_array($data)) {
			$this->where = $data;
		} elseif ($value !== null) {
			if (is_string($this->where)) {
				$this->where = array(); // reset to array if it has been converted to a string by execute()
			}
			$this->where[$data] = $value;
		} else {
			$this->where = $data;
		}
		return $this;
	}

	public function setWhereLogic($logic) {
		$this->whereLogic = ' ' . trim($logic) . ' ';
	}

	/**
	 * @ TODO: Need to support multiple SQL formats
	 * @ TODO: Need to support more than "AND" for WHERE clause grouping somehow
	 * @ TODO: Need to support DISTINCT, GROUP BY, HAVING, ORDER BY, LIMIT,
	 *         other more complex syntax will require the SQL to be manually written (for now?)
	*/
	public function execute($db=null) {
		$db = $db !== null && ? $db : $this->db; //override current database connection if passed
		$table = $this->quoteName($this->table);
		if (count($this->columns)) {
			$tmpColumns = array();
			foreach ($this->columns as $column) {
				$tmpColumns[] = $this->quoteName($column);
			}
			$columns = implode(', ', $tmpColumns);
		} else {
			$columns = '*';
		}
		
		if (is_array($this->where)) {
			$tmpWhere = array();
			foreach ($this->where as $field => $value) {
				$tmpWhere[] = $this->quoteName($field) . '=' . $this->quoteValue($db->escape($value));
			}
			$where = implode($this->whereLogic, $tmpWhere);
		}
		$joins = '';
		if ($this->joins) {
			foreach ($this->joins as $join) {
				$joins .= $join->getSQL();
			}
		}
		
		//this problably needs to be shifted towards it's own method to handle all the different
		//possibilities/functions of the select statement that we plan on supporting
		return sprintf($this->sqlFormat, $columns, $table, $joins, $where); 
	}
}
