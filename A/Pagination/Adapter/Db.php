<?php

class A_Pagination_Adapter_Db implements A_Pagination_Adapter_Interface	{

	public $db;
	public $query;
	public $order_field = '';
	public $order_descending = 0;

	public function __construct ($db, $query)	{
		$this->db = $db;
		$this->query = $query;
	}

	public function getItems ($start, $length)	{
		$result = $this->db->limit ($this->query . $this->constructOrderBy(), $start, $length);
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

	public function setOrderByField ($field, $descending = 0)	{
		$this->order_field = $field;
		$this->order_descending = ($descending === 0 ? 0 : 1);
	}

	public function constructOrderBy()	{
		if ($this->order_field)	{
			return ' ORDER BY ' . $this->order_field . ' ' . ($this->order_descending ? 'DESC' : 'ASC');
		}
	}

}