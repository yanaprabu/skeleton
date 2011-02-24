<?php
/**
 * Datasource access class for pager using the A/Db/* connection classes  
 * 
 * @package A_Pagination 
 */

class A_Pagination_Adapter_Db extends A_Pagination_Adapter_Base	{

	public function getNumItems()	{
		$sql = preg_replace ('#SELECT\s+(.*?)\s+FROM#i', 'SELECT COUNT(*) AS count FROM', $this->query);
		$result = $this->db->query($sql);
		if (!$result->isError())	{
			if ($row = $result->fetchRow())	{
				return $row['count'];
			}
		}
	}

	public function getItems ($start, $length)	{
		$start = $start > 0 ? --$start : 0;				// pager is 1 based, LIMIT is 0 based
		$sql = $this->db->limit($this->query . $this->constructOrderBy(), $length, $start);
		$result = $this->db->query($sql);
		if (!$result->isError() && $result->numRows() > 0)	{
			$rows = array();
			while ($row = $result->fetchRow())	{
				$rows[] = $row;
			}
			return $rows;
		}

	}

}