<?php
/**
 * Datasource access class for pager using the mysql extension  
 * 
 * @package A_Pagination 
 */

class A_Pagination_Adapter_Mysql extends A_Pagination_Adapter_Base	{
	protected $numrows = 0;

    public function getNumItems() {
    	if ($this->numrows == 0) {
	        $query = preg_replace('#SELECT\s+(.*?)\s+FROM#i', 'SELECT COUNT(*) AS count FROM', $this->query);
	        $rs = mysql_query($query, $this->db);
	
	        if ($rs && $row = mysql_fetch_assoc($rs)) {
	            $this->numrows = $row['count'];
	        } else {
	            $this->numrows = 0;
	        }
    	}
        return $this->numrows;    
    }

    public function getItems($start, $length) {
		$start = $start > 0 ? --$start : 0;				// pager is 1 based, LIMIT is 0 based
        $query = $this->query . " LIMIT $length OFFSET $start";
        $rs = mysql_query($query, $this->db);

        $rows = array();
        while ($row = mysql_fetch_assoc($rs)) {
            $rows[] = $row;
        }
        return $rows;
    }
}

