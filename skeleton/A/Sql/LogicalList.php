<?php
include_once 'A/Sql/Statement.php';

class A_Sql_LogicalList extends A_Sql_Statement {
	protected $data = array();

	public function addExpression($arg1, $arg2=null, $arg3=null) {
		if ($arg1) {	
			if ($arg3 === null && !is_array($arg2)) {
				$logic = 'AND';		// if 3rd null and arg2 not an array then it is ('field', 'value')
			} else {
				$logic = $arg1;		// if 3rd arg is set then it is logic
				$arg1  = $arg2;		// move args down
				$arg2  = $arg3;
			}
				
			include_once('A/Sql/Expression.php');
			$expression = new A_Sql_Expression($arg1, $arg2);
			$this->escapeListeners[] = $expression;
			if (count($this->data)) {
				$this->data[] = $logic;
	        }
			$this->data[] = $expression;
		}    
        return $this;
	}
	
	
	public function render() {
		$this->notifyListeners();

		$output = array();
		if (!count($this->data)) return;
		foreach ($this->data as $data) {
			$output[] = is_object($data) ? '('. $data->render() .')' : strtoupper($data);
		}
		return implode(' ', $output);
	}
}