<?php
/**
 * Union.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Sql_Union
 * 
 * Generate SQL unions
 * 
 * @package A_Sql
 */
class A_Sql_Union extends A_Sql_Statement
{

	protected $selects = array();
	
	public function select($select = null)
	{	
		if (!$select) {
			$select = new A_Sql_Select();
		}
		$this->selects[] = $select;
		$this->addListener($select);
		return $select;
	}
	
	public function orderBy($columns)
	{
		$this->orderby = new A_Sql_Orderby($columns);	
		return $this;
	}	
	
	public function render()
	{
		$this->notifyListeners();
		
		if (count($this->selects)) {
			$maxColumns = 0;
			foreach ($this->selects as $select) {
				$columnCount = count($select->getColumns());
				$maxColumns = $columnCount > $maxColumns ? $columnCount : $maxColumns;
			}
			
			foreach ($this->selects as $select) {	
				$columns = $select->getColumns();
				$select->columns(array_merge($columns, array_diff_key(array_fill(0, $maxColumns, 'null'), $columns)));
			}			
		}
		
		$orderby = '';
		if ($this->orderby instanceof A_Sql_Orderby) {
			$orderby = ' '. $this->orderby->render();
		}
		
		return "(". implode(") UNION (", $this->selects) .")$orderby"; 
	}

}
