<?php

class A_Sql_Statement {
	protected $db;
	protected $escapeListeners = array();	
		
	public function setDb($db) {
		$this->db = $db;
		if (count($this->escapeListeners)) {
			foreach ($this->escapeListners[0] as $listener) {
				$listener->setDb($this->db);
			}
		}
		return $this;
	}
}