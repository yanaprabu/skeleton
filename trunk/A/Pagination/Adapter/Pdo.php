<?php
#include_once 'A/Pagination/Adapter/Abstract.php';

/**
 * Datasource access class for pager using ADODB  
 * 
 * @package A_Pagination 
 */

class A_Pagination_Adapter_Pdo extends A_Pagination_Adapter_Abstract	{

	public function getItems ($start, $length)	{
        $query = $this->query . $this->constructOrderBy() . " LIMIT :length OFFSET :start";
		$stmt = $db->prepare($query);
	    $stmt->bindParam(':start', $start, PDO::PARAM_INT);
	    $stmt->bindParam(':length', $length, PDO::PARAM_INT);
	    $stmt->execute();
	    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$rows = array();
		foreach ($result as $row)	{
			$rows[] = $row;
		}
		return $rows;

	}

	public function getNumItems()	{
	    $query = preg_replace('#SELECT\s+(.*?)\s+FROM#i', 'SELECT COUNT(*) AS count FROM', $this->query);
		$stmt = $this->db->prepare($query);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_COLUMN);
		return $count;
	}

}