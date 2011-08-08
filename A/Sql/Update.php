<?php
/**
 * Update.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Sql_Update
 * 
 * Generate SQL UPDATE statement
 * 
 * @package A_Sql
 */
class A_Sql_Update extends A_Sql_Statement
{

	protected $table;
	protected $where;
	protected $joins = array();	
	protected $set;
	
	/**
	 * Constructor
	 * 
	 * @param string $table Table name
	 * @param array $bind Column-value pairs
	 * @param array $where Where statement
	 */
	public function __construct($table=null, $bind = array(), $where = array())
	{
		$this->table($table);
		if($bind)	{
			$this->set($bind);
		}
		$this->where($where);
	}
	
	public function table($table)
	{
		$this->table = new A_Sql_From($table);
		return $this;
	}	
	
	public function set($data, $value=null)
	{
		if (!$this->set) {	
			$this->set = new A_Sql_Set();
		}
		$this->set->addExpression($data, $value);
		return $this;
	}
	
	public function join($table1, $column1, $table2, $column2)
	{
		$this->joins[$table2] = new A_Sql_Join($table1, $column1, $table2, $column2);
		return $this;
	}	
	
	public function where($arg1, $arg2=null, $arg3=null)
	{
		if ($arg1) {
			if (!$this->where) {	
				$this->where = new A_Sql_Where();
			}
			$this->where->addExpression($arg1, $arg2, $arg3);
		}
		return $this;		
	}
	
	public function orWhere($data, $value=null)
	{
		if (!$this->where) {
			$this->where = new A_Sql_Where();
		}
		$this->where->addExpression('OR', $data, $value);
		return $this;		
	}
	
	public function render()
	{
		if (!$this->table || !$this->set) return;
		$this->notifyListeners();
		
		$table = $this->table->render();
		$joins = ''; //not implemented
		$set 	 = $this->set->setDb($this->db)->render();
		$where   = $this->where ? $this->where->setDb($this->db)->render() : '';		
		return "UPDATE $table$set$joins$where";
	}
	
	public function __toString()
	{
		return $this->render();
	}

}
