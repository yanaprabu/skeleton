<?php
/**
 * Tabledatagateway.php
 *
 * @package  A_Db
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Db_Tabledatagateway
 * 
 * Datasource access using the Table Data Gateway pattern
 */
class A_Db_Tabledatagateway
{

	protected $db;
	protected $table = '';
	protected $key = 'id';
	protected $columns = '*';
	protected $errorMsg = '';
	public $sql = '';
	protected $num_rows = 0;
	protected $update = null;
	protected $insert = null;
	
	public function __construct($db, $table=null, $key=null)
	{
		$this->db = $db;
		$this->table($table);
		$this->key($key);
		$this->select = new A_Sql_Select();
		$this->select->from($this->getTable());
	}
	
	public function table($table=null)
	{
		if ($table) {
			$this->table = $table;
		} elseif ($this->table == '') {
			$this->table = strtolower(get_class($this));
		}
		return $this;
	}
	
	public function getTable()
	{
		return $this->table;
	}
	
	public function key($key='')
	{
		$this->key = $key ? $key : 'id';
		return $this;
	}
	
	public function getKey()
	{
		return $this->key;
	}
	
	public function columns($columns)
	{
		$this->columns = $columns;
		return $this;
	}
	
	public function where($arg1=null, $arg2=null, $arg3=null)
	{
		if (isset($arg1)) {
			$this->select->where($arg1, $arg2, $arg3);
		} else {
			$this->select->where();				// no args - clear where
		}
		return $this;
	}
	
	public function find()
	{
		$this->select->where();			// clear where clause
		
		$args = func_get_args();
		// if params then where condition passed
		if (count($args)) {
			if (is_array($args[0])) {
				$args = $args[0];
			} elseif (! isset($args[1])) {	// single scalar param is key search
				$args = array($this->key => $args[0]);
			}
			$this->where($args);
		}
		
		$this->sql = $this->select
			->columns($this->columns)
			->from($this->getTable())
			->render();
		$result = $this->db->query($this->sql);
		if (!$result->isError()) {
			$this->num_rows = $result->numRows();
		} else {
			$this->errorMsg = $result->getErrorMsg();
			$this->num_rows = 0;
		}
		return $result;
	}
	
	public function update($data, $where='')
	{
		if ($data) {
			if (! isset($this->update)) {
				$this->update = new A_Sql_Update($this->getTable());
			}
			$this->update->setDb($this->db)->set($data);
			if ($where) {
				if (is_array($where)) {
					$this->update->where($where);
				} else {
					$this->update->where($this->key, $where);
				}
			}
			$this->sql = $this->update->render();
			return $this->db->query($this->sql);
		}
	}
	
	public function insert($data)
	{
		if ($data) {
			if (! isset($this->insert)) {
				$this->insert = new A_Sql_Insert($this->getTable());
			}
			$this->sql = $this->insert->setDb($this->db)->values($data)->render();
			return $this->db->query($this->sql);
		}
	}
	
	public function save($data)
	{
		if ($data) {
			if (isset($data[$this->key]) && $data[$this->key]) {
				$this->update($data, $data[$this->key]);
			} else {
				$this->insert($data);
			}
		}
	}
	
	public function delete($id)
	{
		if ($id) {
			$this->sql = "DELETE FROM {$this->table} WHERE {$this->key}='$id'";
			$this->db->query($this->sql);
		}
	}
	
	public function numRows()
	{
		return $this->num_rows;
	}
	
	public function isError()
	{
		return $this->db->isError() || ($this->errorMsg != '');
	}
	
	public function getErrorMsg()
	{
		return $this->db->getErrorMsg() . $this->errorMsg;
	}

}
