<?php

class A_Sql_Statement {
	protected $db = null;
	protected $escapeListeners = array();	
	
	protected function condition(&$list, $arg1, $arg2=null, $arg3=null) {
		if ($arg1) {	
			if (($arg3 === null) && ! is_array($arg2)) {
				$logic = 'AND';		// if 3rd null and arg2 not an array then it is ('field', 'value')
			} else {
				$logic = $arg1;		// if 3rd arg is set then it is logic
				$arg1 = $arg2;		// move args down
				$arg2 = $arg3;
			}
				
			include_once('A/Sql/Expression.php');
			$expression = new A_Sql_Expression($arg1, $arg2);
			$this->escapeListeners[] = $expression; 		
			if ($list) {
				$list[] = $logic;
	        }
			$list[] = $expression;
		}    
        return $this;
    }
/*
    protected function condition(&$list, $arg1, $arg2=null, $arg3=null) {
		if (!$arg1) return $this; //no need to proceed if arguments are empty
		$arguments = array_filter(array($arg1, $arg2, $arg3));
		$numArguments = count($arguments);
		
		if ($numArguments <= 2 && !is_array(isset($arguments[1]) ? $arguments[1] : null)){
			array_unshift($arguments, 'AND');
		}

		include_once('A/Sql/Expression.php');
		$expression = new A_Sql_Expression($arguments[1], isset($arguments[2]) ? $arguments[2] : null);
		$this->escapeListeners[] = $expression; 		
		if ($list) {
			$list[] = $arguments[0];
		}
		$list[] = $expression;
        return $this;
    }	
*/
		
	public function setDb($db) {
		$this->db = $db;
		return $this;
	}

	protected function notifyListeners() {
		if (count($this->escapeListeners)) {
			foreach ($this->escapeListeners as $listener) {
				$listener->setDb($this->db);
			}
		}
	}
}