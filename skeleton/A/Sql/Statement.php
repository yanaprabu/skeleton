<?php

class A_Sql_Statement {
	protected $db = null;
	protected $escapeListeners = array();	
	
	protected function condition(&$list, $arg1, $arg2=null, $arg3=null) {
		if (!$arg1) return $this; //no need to proceed if arguments are empty
		$arguments = array_filter(array($arg1, $arg2, $arg3));
		$numArguments = count($arguments);
		if ($numArguments < 2) {
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