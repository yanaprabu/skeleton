<?php
/**
 * Datasource access class for Pager using the mysql_* functions 
 * 
 * @package A_Pager 
 */

class A_Pager_MySQL {
	protected $query;
	protected $dblink;
	protected $numrows = 0;

    public function __construct($query, $dblink=null) {
        $this->query = $query;
        $this->dblink = $dblink;
    }

    public function getNumRows() {
    	if ($this->numrows == 0) {
	        $query = preg_replace('#SELECT\s+(.*?)\s+FROM#i', 'SELECT COUNT(*) AS count FROM', $this->query);
	        $rs = mysql_query($query, $this->dblink);
	
	        if ($rs && $row = mysql_fetch_assoc($rs)) {
	            $this->numrows = $row['count'];
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
        $query = $this->query . " LIMIT {$rowcount} OFFSET {$begin}";
        $rs = mysql_query($query, $this->dblink);

        $rows = array();
        while ($row = mysql_fetch_assoc($rs)) {
            $rows[] = $row;
        }
        return $rows;
    }
}

