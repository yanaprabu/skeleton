<?php
/**
 * Statement.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Sql_Statement
 * 
 * Base class for SQL classes to provide Observer support for escaping
 * 
 * @package A_Sql
 */
class A_Sql_Statement
{

	protected $db;
	protected $escapeListeners = array();	
	
	public function __construct($db=null)
	{
		if (is_object($db)) {
			$this->db = $db;
		}
	}
	
	public function setDb($db)
	{
		$this->db = $db;
		return $this;
	}
	
	public function getDb()
	{
		if (!$this->db) {
			return false;	
		}
		return $this->db;
	}
	
	protected function addListener($listener)
	{
		$this->escapeListeners[] = $listener;
	}
	
	protected function notifyListeners()
	{
		if (count($this->escapeListeners)) {
			foreach ($this->escapeListeners as $listener) {
				$listener->setDb($this->db);
			}
		}
	}

}
