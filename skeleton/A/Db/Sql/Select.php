<?php

include_once 'A/Db/Sql/Common.php';

class A_Db_Sql_Select extends A_Db_Sql_Common {
	protected $db;
	protected $table;
	protected $columns = array();
	protected $where = array();
	protected $joins = array();
	protected $sqlFormat = 'SELECT %s FROM %s %s WHERE %s';
	
	public function __construct($db=null) {
		$this->db = $db !== null ? $db : $this;
	}
		
	public function columns() {
		if (func_num_args()) {
			$args = func_get_args();
			if (!array_search('*', $args)) { //if wildcard was passed, ignore it
				$this->columns = is_array($args[0]) ? $args[0] : $args;
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
				// reset to array if it has been converted to a string by execute()
				$this->where = array();
			}
			$this->where[$data] = $value;
		} else {
			$this->where = $data;
		}
		return $this;
	}

	/**
	 * @ TODO: Need to support multiple SQL formats
	 * @ TODO: Need to support more than "AND" for WHERE clause grouping somehow
	*/
	public function execute($db=null) {
		$db = $db !== null ? $this->db : $db;
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
			$tmp = array();
			foreach ($this->where as $field => $value) {
				$tmp[] = $this->quoteName($field) . '=' . $this->quoteValue($value); //$this->quoteValue($db->escape($value));
			}
			$where = implode(' AND ', $tmp);
		}
		$joins = '';
		if ($this->joins) {
			foreach ($this->joins as $join) {
				$joins .= $join->getSQL();
			}
		}
		
		return sprintf($this->sqlFormat, $columns, $table, $joins, $where);
	}
}
