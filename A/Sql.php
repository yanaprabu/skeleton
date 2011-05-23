<?php
/**
 * SQL interface library
 *
 * @package  A
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Sql
 *
 * Wrap select, insert, update, delete SQL generators in a single inteface
 */
class A_Sql
{
	protected $connection;
	protected $select = null;
	protected $insert = null;
	protected $update = null;
	protected $delete = null;
	
	/**
	 * __construct
	 *
	 * @param mixed $connection MySQL connection to use
	 */
	public function __construct($connection=null)
	{
		$this->connection = $connection;
	}
	
	/**
	 * __call
	 *
	 * @param mixed $name ???
	 * @param mixed $args ???
	 */	
	public function __call($name, $args)
	{
		switch ($name) {
			case 'select':
				if (!$this->select) {
					$this->select = new A_Sql_Select($this->connection);
				}
				return $this->select->columns($args);
			break;
			case 'insert':
				if (!$this->insert) {
					$this->insert = new A_Sql_Insert($this->connection);
				}
				return $this->insert->table($args);
			break;
			case 'update':
				if (!$this->update) {
					$this->update = new A_Sql_Update($this->connection);
				}
				return $this->update->table($args);
			break;
			case 'delete':
				if (!$this->delete) {
					$this->delete = new A_Sql_Delete($this->connection);
				}
				return $this->delete->table($args);
			break;
		}
	}

}
