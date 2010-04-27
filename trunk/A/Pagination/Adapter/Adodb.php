<?php
#include_once 'A/Pagination/Adapter/Abstract.php';

/**
 * Datasource access class for pager using ADODB  
 * 
 * @package A_Pagination 
 */

class A_Pagination_Adapter_Adodb {
	protected $numrows = 0;

    public function getNumItems() {
    	if ($this->numrows == 0) {
	        $query = preg_replace('#SELECT\s+(.*?)\s+FROM#i', 'SELECT COUNT(*) AS count FROM', $this->query);
	        $row = $this->db->GetArray($query);
	        if ($row) {
	            $this->numrows = $row[0]['count'];
	        } else {
	            $this->numrows = 0;
	        }
    	}
        return $this->numrows;    
    }

    public function getItems($start, $length) {
        $rs = $this->db->SelectLimit($this->query, $length, $start-1);	// pager is 1 based, LIMIT is 0 based
        $rows = $rs->GetArray();
        return $rows;
    }
}

