<?php
#require_once 'A/Sql/Statement.php';
/**
 * Class for SQL delete query generation.
 *
 * @category A
 * @package A_Sql
 * @subpackage Delete
 * @license    BSD
 * @version    $Id:$
 */
class A_Sql_Delete extends A_Sql_Statement{
	
	/**
	 * Table name
	 * @var string
	 */
	protected $table = null;
	
	/**
	 * Where clause
	 * @var string
	 */
	protected $where = null;
	
	/**
	 * Where expression
	 * @var unknown_type
	 */
	protected $whereExpression;
	
	/**
	 * Class constructor
	 *
	 * @param string $table
	 * @param array $where
	 */
	public function __construct($table=null, $where=array()) {
		$this->table($table);
		$this->where($where);
	}
	
	public function table($table) {
		if ($table) {
			#include_once('A/Sql/Table.php');
			$this->table = new A_Sql_From($table);
		}
		return $this;
	}

	public function where($arg1, $arg2=null, $arg3=null) {
		if (!$this->where) {
			#include_once('A/Sql/Where.php');		
			$this->where = new A_Sql_Where();
		}
		$this->where->addExpression($arg1, $arg2, $arg3);
		return $this;		
	}

	public function orWhere($data, $value=null) {
		if (!$this->where) {
			#include_once('A/Sql/Where.php');
			$this->where = new A_Sql_Where();
		}
		$this->where->addExpression('OR', $data, $value);
		return $this;		
	}
	
	function render() {
		if ($this->table) {
			$table = $this->table->render();
			$where = $this->where ? ' '. $this->where->setDb($this->db)->render() : '';
			return "DELETE FROM $table$where";
		}
	}

	public function __toString() {
		return $this->render();
	}

}
