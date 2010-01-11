<?php
#include_once 'A/Pagination/Adapter/Abstract.php';

/**
 * Datasource access class for pager using ADODB  
 * 
 * @package A_Pagination 
 */

class A_Pagination_Adapter_Db extends A_Pagination_Adapter_Abstract	{

	public function getItems ($start, $length)	{
		$sql = $this->db->limit($this->query . $this->constructOrderBy(), $length, $start);
		$result = $this->db->query ($sql);
		if (!$result->isError() && $result->numRows() > 0)	{
			$rows = array();
			while ($row = $result->fetchRow())	{
				$rows[] = $row;
			}
			return $rows;
		}

	}

	public function getNumItems()	{
		$query = preg_replace ('#SELECT\s+(.*?)\s+FROM#i', 'SELECT COUNT(*) AS count FROM', $this->query);
		$result = $this->db->query ($query);
		if (!$result->isError())	{
			if ($row = $result->fetchRow())	{
				return $row['count'];
			}
		}
	}

}