<?php

class A_Sql_Join {
	protected $joins = array();
	protected $joinFormat = '%s JOIN %s ON %s.%s=%s.%s';
	protected $joinTypes = array('INNER', 'LEFT', 'RIGHT', 'FULL', 'CROSS', 'NATURAL');

	public function join($table1, $table2, $type) {
		if (!in_array($type, $this->joinTypes)) { //invalid join type
			return;
		}
		$this->joins[] = array('type' => strtoupper($type), 'table1' => $table1, 'table2' => $table2, 'on' => null); 
	}
	
	public function on($argument1, $argument2=null) {
		$joinkey = count($this->joins)-1;
		if (!isset($this->joins[$joinkey])) { //no join has been set yet
			return;
		}
		if (!$this->joins[$joinkey]['on']) {
			require_once 'A/Sql/LogicalList.php';						
			$this->joins[$joinkey]['on'] = new A_Sql_LogicalList();
			$this->joins[$joinkey]['on']->setEscape(false);
		}		
		if (is_array($argument1)) {
			if (!count($argument1)) {  //empty array of expressions was passed
				return;
			}
			foreach($argument1 as $column1 => $column2) {
				$this->joins[$joinkey]['on']->addExpression(
					$this->prependTableAlias($this->joins[$joinkey]['table1'], $column1), 
					$this->prependTableAlias($this->joins[$joinkey]['table2'], $column2)
				);
			}
		} else {
			$this->joins[$joinkey]['on']->addExpression(
				$this->prependTableAlias($this->joins[$joinkey]['table1'], $argument1), 
				$this->prependTableAlias($this->joins[$joinkey]['table2'], $argument2)
			);
		}
	}

	public function render() {
		$return = '';
		foreach ($this->joins as $join) {
			$on = $join['on'] ? ' ON '. $join['on']->render() : '';
			$return .= ($return ? ' ' : '') . $join['type'] .' JOIN '. $join['table1'] . $on;
		}
		return $return;
	}

	protected function prependTableAlias($alias, $table) {
		if (strpos($table, '.')) {
			$pieces = explode('.', $table);
			if (strtolower($pieces[0]) == strtolower($alias)) { //user prepended themselves
				return $table;
			}
			return $alias.'.'. implode('.', $pieces);
		}
		return $alias .'.'. $table;
	}


	public function __toString() {
		return $this->render();
	}
}