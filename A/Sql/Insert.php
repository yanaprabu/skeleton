<?php
/**
 * Insert.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Sql_Insert
 * 
 * Generate SQL INSERT statement
 * 
 * @package A_Sql
 */
class A_Sql_Insert extends A_Sql_Statement
{

	protected $table;
	protected $values;
	protected $columns;
	protected $select;
	protected $onDuplicateKey;
	
	/**
	 * Class constructor
	 *
	 * @param string $table Table name
	 * @param array $bind Column-value pairs
	 */
	public function __construct($table=null, $bind=array())
	{
		$this->table($table);
		if ($bind) {
			$this->columns($bind);	
		}
		
	}
	
	public function table($table)
	{
		$this->table = new A_Sql_From($table);
		return $this;
	}
	
	public function values($data, $value=null)
	{
		if ($data) {
			$this->columns = null;
			$this->select = null;
			$this->values = new A_Sql_Values($data, $value);
		}
		return $this;
	}
	
	public function columns()
	{
		$this->columns = new A_Sql_Columns(func_get_args());
		return $this;
	}
	
	public function select()
	{
		if (!$this->select) {
			$this->select = new A_Sql_Select();
		}
		return $this->select;
	}

	public function updateIfDuplicateKey($columns)
	{
		if (!is_array($columns))
			$columns = array($columns);
		$this->onDuplicateKey = new A_Sql_Onduplicatekey($columns);
		return $this;
	}
	
	public function render($db=null)
	{
		$columns = array();
		if ($this->table) {
			$table = $this->table->render();
			if ($this->values) {
				$insert = "INSERT INTO $table " . $this->values->render();
			} elseif ($this->columns && $this->select) {
				$insert = "INSERT INTO $table (" . $this->columns->render() . ') ' . $this->select->setDb($this->db)->render();
			}
			if ($this->onDuplicateKey) {
				$insert .= ' ' . $this->onDuplicateKey->render();
			}
			return $insert;
		}
	}
	
	public function __toString()
	{
		return $this->render();
	}

}
