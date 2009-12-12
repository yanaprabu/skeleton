<?php
#require_once 'A/Sql/Statement.php';
/**
 * Generate SQL INSERT statement
 * 
 * @package A_Sql 
 */

class A_Sql_Insert extends A_Sql_Statement {
	protected $table;
	protected $values;
	protected $columns;
	protected $select;

	/**
	 * Class constructor
	 *
	 * @param string $table Table name
	 * @param array $bind Column-value pairs
	 */
	public function __construct($table = null, $bind = array()) {
		$this->table($table);
		if ($bind)	{
			$this->columns($bind);	
		}
		
	}
	
	public function table($table) {
		#include_once('A/Sql/Table.php');
		$this->table = new A_Sql_From($table);
		return $this;
	}

	public function values($data, $value=null) {
		if ($data) {
			$this->columns = null;
			$this->select = null;		
			#include_once('A/Sql/Values.php');
			$this->values = new A_Sql_Values($data, $value);
		}
		return $this;
	}
	
	public function columns() {
		#include_once('A/Sql/Columns.php');
		$this->columns = new A_Sql_Columns(func_get_args());
		return $this;
	}

	public function select() {
		if (!$this->select) {
			#include_once('A/Sql/Select.php');
			$this->select = new A_Sql_Select();
		}
		return $this->select;
	}

	public function render($db=null) {
		$columns = array();
		if ($this->table) {
			if (!$this->values) {
				if (!$this->columns || !$this->select) { 
					return;
				}
				$this->values = new A_Sql_Values($this->columns->render(), $this->select->setDb($this->db)->render());
			}
			$values = $this->values->render();
			$table = $this->table->render();
			return "INSERT INTO $table $values";
		}
	}

	public function __toString() {
		return $this->render();
	}

}
