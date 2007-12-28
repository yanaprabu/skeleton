<?php

include_once 'A/Db/Sql/Common.php';

class A_Db_Sql_Select extends A_Db_Sql_Common {
	protected $db;
	protected $table;
	protected $columns = array();
	protected $where = array();

	public function __construct($db=null) {
		$this->db = $db;
	}
	
	public function columns() {
		if (func_num_args()) {
			$callback = create_function('$a', 'return $a !== \'*\';');
			$this->columns = array_filter(func_get_args(), $callback);
		}
		return $this;
	}

	public function from($table) {
		$this->table = $table;
		return $this;
	}

	public function where($data, $value=null) {
		if (is_array($data)) {
			if (count($data)) {
				$this->where = array_merge($this->where, $data);
			}
		} elseif ($value !== null) {
			$this->where[$data] = $value;
		} else {
			$this->where[] = $value;
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
		$columns = count($this->columns) ? '`'. implode('`, `', $this->columns).'`' : '*';
		
		if (is_array($this->where)) {
			$tmp = array();
			foreach ($this->where as $field => $value) {
				$tmp[] = '`'.$field.'`' . '=' . $this->quoteValue($value); //$this->quoteValue($db->escape($value));
			}
			$where = implode(' AND ', $tmp);
		}

		return "SELECT $columns FROM $table WHERE $where";
	}

}

?>