<?php
/**
 * Delete.php
 *
 * @package  A_Sql
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Sql_Delete
 * 
 * Class for SQL delete query generation.
 */
class A_Sql_Delete extends A_Sql_Statement
{

	/**
	 * Table name
	 * 
	 * @var string
	 */
	protected $table = null;
	
	/**
	 * Where clause
	 * 
	 * @var string
	 */
	protected $where = null;
	
	/**
	 * Where expression
	 * 
	 * @var unknown_type
	 */
	protected $whereExpression;
	
	/**
	 * Class constructor
	 *
	 * @param string $table
	 * @param array $where
	 */
	public function __construct($table=null, $where=array())
	{
		$this->table($table);
		$this->where($where);
	}
	
	public function table($table)
	{
		if ($table) {
			$this->table = new A_Sql_From($table);
		}
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
		$this->where('OR', $data, $value);
		return $this;		
	}
	
	function render()
	{
		if ($this->table) {
			$table = $this->table->render();
			$where = $this->where ? $this->where->setDb($this->db)->render() : '';
			return "DELETE FROM $table$where";
		}
	}
	
	public function __toString()
	{
		return $this->render();
	}

}
