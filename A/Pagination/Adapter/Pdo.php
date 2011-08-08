<?php
/**
 * Pdo.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Pagination_Adapter_Pdo
 * 
 * Datasource access class for pager using PDO  
 * 
 * @package A_Pagination 
 */
class A_Pagination_Adapter_Pdo extends A_Pagination_Adapter_Base
{

	public function getNumItems()
	{
		$query = preg_replace('#SELECT\s+(.*?)\s+FROM#i', 'SELECT COUNT(*) AS count FROM', $this->query);
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$count = $stmt->fetch(PDO::FETCH_COLUMN);
		return $count;
	}
	
	public function getItems ($start, $length)
	{
		$start = $start > 0 ? --$start : 0;				// pager is 1 based, LIMIT is 0 based
		$query = $this->query . $this->constructOrderBy() . " LIMIT :length OFFSET :start";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':start', $start, PDO::PARAM_INT);
		$stmt->bindParam(':length', $length, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$rows = array();
		foreach ($result as $row) {
			$rows[] = $row;
		}
		return $rows;
	}

}
