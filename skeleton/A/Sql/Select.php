<?php
require_once 'A/Sql/Statement.php';

class A_Sql_Select extends A_Sql_Statement {
	protected $render = array();
	protected $pieces = array(
		'tables' 	=> null,
		'columns' 	=> null,
		'joins' 	=> null,
		'where' 	=> null,
		'having' 	=> null,
		'groupby' 	=> null,
		'orderby' 	=> null,
	);

	public function columns() {
		require_once 'A/Sql/Columns.php';		
		$this->pieces['columns'] = new A_Sql_Columns(func_get_args());
		return $this;
	}
	
	public function getColumns() {
		if (!$this->pieces['columns']) {
			return array();
		}
		return $this->pieces['columns']->getColumns();
	}	
	
	public function from() {
		require_once 'A/Sql/Table.php';	
		$this->pieces['tables'] = new A_Sql_Table(func_get_args());
		return $this;
	}
	
	public function where($argument1, $argument2=null, $argument3=null) {
		if (!$this->pieces['where']) {
			require_once 'A/Sql/Where.php';		
			$this->pieces['where'] = new A_Sql_Where();
		}
		$this->pieces['where']->addExpression($argument1, $argument2, $argument3);
		return $this;		
	}

	public function orWhere($data, $value=null) {
		if (!$this->pieces['where']) {
			require_once 'A/Sql/Where.php';		
			$this->pieces['where'] = new A_Sql_Where();
		}
		$this->pieces['where']->addExpression('OR', $data, $value);
		return $this;		
	}

	public function having($argument1, $argument2=null, $argument3=null) {
		if (!$this->pieces['having']) {
			require_once 'A/Sql/Having.php';
			$this->pieces['having'] = new A_Sql_Having();
		}
		$this->pieces['having']->addExpression($argument1, $argument2, $argument3);
		return $this;		
	}

	public function orHaving($data, $value=null) {
		if (!($this->pieces['having'] instanceof A_Sql_Having)) {
			require_once 'A/Sql/Having.php';
			$this->pieces['having'] = new A_Sql_Having();
		}
		$this->pieces['having']->addExpression('OR', $data, $value);
		return $this;		
	}	

	public function groupBy($columns) {
		require_once 'A/Sql/Groupby.php';
		$this->pieces['grouby'] = new A_Sql_Groupby($columns);	
		return $this;
	}

	public function orderBy($columns) {
		require_once 'A/Sql/Orderby.php';
		$this->pieces['orderby'] = new A_Sql_Orderby($columns);	
		return $this;
	}

	public function __toString() {
		return $this->render();
	}

	public function render() {
		foreach ($this->pieces as $name => $piece) {
			$output = null;
			if (method_exists($piece, 'render')) {
				if (method_exists($piece, 'setDb')) {
					$piece->setDb($this->getDb());
				}
				$output = $piece->render();
			}
			$this->replace['['.$name.']'] = strlen($output) ? ' '. $output : $output; //add spacing
		}
		
		$sql = "SELECT[columns] FROM [tables][joins][having][where][orderby][groupby]";
		return str_replace(array_keys($this->replace), array_values($this->replace), $sql);
	}
}
