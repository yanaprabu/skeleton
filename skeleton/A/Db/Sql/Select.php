<?php
include_once 'A/Db/Sql/Common.php';

class A_Db_Sql_Select extends A_Db_Sql_Common {	protected $columns = null;	protected $tables = null;

	public function __construct() {
	}
		
	function columns($param) {
		return $this;
	}

	function from($param) {
		return $this;
	}

	function where($condition, $value='') {
		return $this;
	}

}
