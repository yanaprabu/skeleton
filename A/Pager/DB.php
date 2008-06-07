<?php

class A_Pager_DB {
	protected $query;
	protected $db;
	protected $numrows = 0;
	protected $order_by_field = '';
	protected $order_by_descending = 0;

    public function __construct($query, $db) {
        $this->query = $query;
        $this->db = $db;
    }

    public function getNumRows() {
    	if ($this->numrows == 0) {
	        $query = preg_replace('#SELECT\s+(.*?)\s+FROM#i', 'SELECT COUNT(*) AS count FROM', $this->query);
	        $result = $this->db->query($query);
	        if (! $result->isError()) {
	        	$row = $result->fetchRow();
	        	if ($row) {
		            $this->numrows = $row['count'];
		        }
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
        $rows = array();
        $rowcount = $end - $begin;
        $result = $this->db->limitQuery($this->query . $this->orderBy(), $begin, $rowcount);
        if (! $result->isError() && ($result->numRows() > 0)) {
        	while ($row = $result->fetchRow()) {
        		$rows[] = $row;
        	}
        }
        return $rows;
    }

    public function setOrderBy($field, $descending=0) {
		$this->order_by_field = $field;
		$this->order_by_descending = $descending;
		return $this;
    }

    public function orderBy() {
    	if ($this->order_by_field) {
    		$str = ' ORDER BY ' . $this->order_by_field;
	    	if ($this->order_by_descending) {
	    		$str .= ' DESC';
			}
			return $str;
    	} else {
    		return '';
    	}
		return $this;
    }

}

