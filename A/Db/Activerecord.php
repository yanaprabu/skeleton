<?php
/**
 * Activerecord.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	Jonah <jonah@nucleussystems.com>, Christopher <christopherxthompson@gmail.com>
 */

/**
 * A_Db_Activerecord
 *
 * DataSource access using the Active Record pattern.
 *
 * @package A_Db
 */
class A_Db_Activerecord extends A_Collection
{

	public $sql = '';

	protected $db = null;
	protected $table;
	protected $key = 'id';
	protected $select;
	protected $errorMsg = '';
	protected $columns = '*';
	protected $num_rows = 0;
	protected $is_loaded = false;

	protected static $globaldb = null;

	# Do we need a A_Db_Activerecord_List class that contains an array of A_Db_Activerecord objects?  It would a separate array to iterate over

	public function __construct($db=null, $table='', $key='id')
	{
		if ($db) {
			$this->db = $db;
		} elseif (self::$globaldb) {
			$this->db = self::$globaldb;
		}
		$this->table($table);
		$this->key = $key;
		$this->select = new A_Sql_Select();
		$this->select->from($this->getTable());
	}

	public function setDb($db)
	{
		if (isset($this)) {
			$this->db = $db;
		} else {
			self::$globaldb = $db;
		}
		return $this;
	}

	public function table($table=null)
	{
		if ($table) {
			$this->table = $table;
		} else {
			$this->table = strtolower(get_class($this));
		}
		return $this;
	}

	public function getTable()
	{
		return $this->table;
	}

	public function key($key=null)
	{
		if ($key) {
			$this->key = $key;
		} elseif (!$this->key) {
			$this->key = 'id';
		}
		return $this;
	}

	public function getKey()
	{
		return $this->key;
	}

	public function setColumns($columns)
	{
		$this->columns = $columns;
		return $this;
	}

	public function where()
	{
		$args = func_get_args();
		// allow one param that is array of args
		if (is_array($args[0])) {
			$args = $args[0];
		}
		$nargs = count($args);
		if ($nargs == 1) {
			// find match for key
			$this->select->where($this->key . '=', $args[0]);
		} else {
			$this->select->where($args[0], $args[1], isset($args[2]) ? $args[2] : null);
		}
		return $this;
	}

	public function find()
	{
		$allrows = array();

		$args = func_get_args();
		// if params then where condition passed
		if (count($args)) {
			$this->where($args);
		}

		$this->sql = $this->select->render();
		$result = $this->db->query($this->sql);
		if ($result->isError()) {
			$this->errorMsg = $result->getErrorMsg();
			$this->is_loaded = false;
		} else {
			$this->_data = $result->fetchRow();
			$this->num_rows = count($this->_data);
			$this->is_loaded = true;
		}
		return $this->errorMsg;
	}

	public function save($data=array())
	{
		if ($data) {
			$this->_data = $data;
		}
		if (!$this->is_loaded) {
			$insert = new A_Sql_Insert();
			$insert->table($this->table)->values($this->_data);
			$this->sql = $insert->render();
			$this->db->query($this->sql);
			$try_update = !$this->db->isError();
		}
		if (isset($this->_data[$this->key]) && ($this->is_loaded || $try_update)) {
			$update = new A_Sql_Update();
			$update->table($this->table)->set($this->_data)->where($this->key, $this->_data[$this->key]);
			$this->sql = $update->render();
			$this->db->query($this->sql);
		}
		return $this;
	}

	public function delete()
	{
		if (isset($this->_data[$this->key]) && $this->is_loaded) {
			$delete = new A_Sql_Delete();
			$delete->table($this->table)->where($this->key, $this->_data[$this->key]);
			$this->sql = $delete->render();
			$this->db->query($this->sql);
		}
		return $this;
	}

}
