<?php
/**
 * Datasource access class for pager using ADODB  
 * 
 * @package A_Pager 
 */

class A_Pager_ADODB {
	protected $query;
	protected $db;
	protected $numrows = 0;

    public function __construct($query, $db) {
        $this->query = $query;
        $this->db = $db;
    }

    public function getNumRows() {
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

    public function getRows($begin, $end) {
        if ($begin > 0) {
        	--$begin;			// make zero based
        } else {
        	$begin = 0;
        }
        $rowcount = $end - $begin;
        $rs = $this->db->SelectLimit($this->query, $rowcount, $begin);
        $rows = $rs->GetArray();
        return $rows;
    }
}

