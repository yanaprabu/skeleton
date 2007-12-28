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
			// if an array of columns was passed, use it
			if (is_array($args[0])) {
				$args = $args[0];
			}
			$callback = create_function('$a', 'return $a !== \'*\';');
			$this->columns = array_filter($args, $callback);
		}
		return $this;
	}

	public function from($table) {
		$this->table = $table;
		return $this;
	}

	function join($join) {
		if ($join instanceof A_Db_Sql_Join) {
			$this->joins[] = $join;
		}
		return $this;
	}

	function where($data, $value=null) {
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
		$table = '`'. $this->table .'`';
		if (is_array($this->columns)) {
			$str = implode('`, `', $this->columns);
			$columns = count($this->columns) ? ('`'. $str .'`') : '*';
		} else {
			$columns = $this->columns;
		}
		
		if (is_array($this->where)) {
			$tmp = array();
			foreach ($this->where as $field => $value) {
				$tmp[] = '`'.$field.'`' . '=' . $this->quoteValue($value); //$this->quoteValue($db->escape($value));
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
