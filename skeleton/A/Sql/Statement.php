<?php

class A_Sql_Statement {
	protected $db;
	protected $escapeListeners = array();	

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