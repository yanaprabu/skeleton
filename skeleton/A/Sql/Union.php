<?php

class A_Sql_Union extends A_Sql_Select {
	protected $selects = array();
	
	public function select($select = null) {	
		if (!($select instanceof A_Sql_Select)) {
			require_once 'A/Sql/Select.php';
			$select = new A_Sql_Select();
		}
		$this->escapeListeners[] = $select;
		$this->selects[] = $select;
		return $select;
	}
	
	public function orderBy($columns) {
		require_once 'A/Sql/Orderby.php';
		$this->orderby = new A_Sql_Orderby($columns);	
		return $this;
	}	
	
	public function render() {
		$this->notifyListeners();

		if (count($this->selects)) {
			$maxColumns = 0;
			foreach ($this->selects as $select) {
				$columnCount = count($select->getColumns());
				$maxColumns = $columnCount > $maxColumns ? $columnCount : $maxColumns;
			}
			foreach ($this->selects as $select) {	
				$select->columns(array_merge($select->getColumns(), array_fill(0, $maxColumns-1, 'null')));
			}			
		}
		
		$orderby = '';
		if ($this->orderby instanceof A_Sql_Orderby) {
			$orderby = ' '. $this->orderby->render();
		}
		
		return "(". implode(") UNION (", $this->selects) .")$orderby"; 
	}
}

?>