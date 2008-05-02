<?php

require_once 'A/Sql/Statement.php';

class A_Sql_Update extends A_Sql_Statement {
	protected $sqlFormat = 'UPDATE %s SET %s%s';
	protected $table;
	protected $data;
	protected $where;
	
	public function table($table) {
		include_once('A/Sql/Table.php');
		$this->table = new A_Sql_Table($table);
		return $this;
	}

	public function set($data, $value=null) {
		include_once('A/Sql/Expression.php');
		$this->data[] = new A_Sql_Expression($data, $value);	
		$this->escapeListeners[] = end($this->data);	
		return $this;
	}
	
	public function where($data, $value=null, $logic = 'AND') {
		include_once('A/Sql/Expression.php');
		$this->where[] = array(new A_Sql_Expression($data, $value), $logic);	
		$this->escapeListeners[] = end($this->where);	
		return $this;
	}
	
	public function orWhere($data, $value=null) {
		include_once('A/Sql/Expression.php');
		$this->where[] = array(new A_Sql_Expression($data, $value), 'OR');	
		$this->escapeListeners[] = end($this->where);	
		return $this;		
	}

	public function render() {
		if ($this->table) {			
			if ($this->data) {
				$sets = array();
				if (count($this->data)) {
					foreach ($this->data as $data) {
						$sets[] = $data->render();
					}
				}	

				$logicTypes = array('having' => $this->having, 'where' => $this->where);
				$logicRender = array('having' => null, 'where' => null);
				foreach ($logicTypes as $type => $stack) {
					if (count($stack)) {
						foreach ($stack as $condition) {
							list($expression, $logical) = $condition;
							$logical = ' '. strtoupper($logical) .' ';
							$logicRender[$type][] = (count($logicRender[$type]) ? $logical : '') . $expression->render(); //dont add the first logical statement
						}
					}
				}
				
				$having = count($logicRender['having']) ? ' HAVING ' . implode(' ', $logicRender['having']) : '';
				$where =  count($logicRender['where'])  ? ' WHERE ' . implode(' ', $logicRender['where']) : '';
							
				$table = $this->table->render();
				$set = implode(', ', $sets);
				$joins = ''; //not implemented
				$having = ''; //not implemented

				return "UPDATE $table SET $joins$set$columns FROM $table$joins$having$where";
			}
		}
	}

	public function __toString() {
		return $this->render();
	}

}
