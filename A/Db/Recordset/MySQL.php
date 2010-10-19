<?php
/**
 * Database result set for MySQL select, show, or desc queries
 * 
 * @package A_Db_Recordset
 * @author Jonah Dahlquist <jonah@nucleussystems.com>
 */
class A_Db_Recordset_MySQL extends A_Db_Recordset_Abstract {

	protected $result = null;
	
	public function setResult ($result) {
		$this->result = $result;
	}
		
	public function fetchRow ($mode=null) {
		if ($this->result) {
			return mysql_fetch_assoc($this->result);
		}
	}
		
	public function fetchObject ($class=null) {
		if ($this->result) {
			return mysql_fetch_object($this->result, $class);
		}
	}
		
	public function fetchAll ($class=null) {
		$rows = array();
		if ($this->result) {
			while ($row = mysql_fetch_assoc($this->result)) {
				$rows[] = $row;
			}
		}
		return $rows;
	}
		
	public function numRows() {
		if ($this->result) {
			return mysql_num_rows($this->result);
		} else {
			return 0;
		}
	}
		
	public function numCols() {
		if ($this->result) {
			return mysql_num_cols($this->result);
		} else {
			return 0;
		}
	}
	
}