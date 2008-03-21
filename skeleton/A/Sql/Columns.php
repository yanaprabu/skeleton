<?php

class A_Sql_Columns {
	protected $columns = array();
	
	public function __construct(array $arguments) {
		$this->arguments = $arguments;
	}
	
	public function render() {
		if (!array_search('*', $this->arguments)) {
			$this->columns = is_array($this->arguments[0]) ? $this->arguments[0] : $this->arguments;
		}
		if (!count($this->columns)) {
			$this->columns = array('*');
		}
		return implode(', ', $this->columns);
	}

	public function __toString() {
		return $this->render();
	}

}
