<?php
/**
 * String.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	Cory Kaufman
 */

/**
 * A_Sql_String
 * 
 * Generate SQL using strings with replaceable tags
 * 
 * @package A_Sql
 */
class A_Sql_String
{

	protected $_sql = '';
	protected $_prefix = ':';
	protected $_suffix = '';
	
	public function __construct($string)
	{
		$this->_sql = $string;
	}
	
	public function replace($search, $replace, $join_delimiter=',')
	{
		if (is_array($replace)) {
			if (count($replace) == 0) {
				$replace = '';
			} elseif (count($replace) == 1) {
				$replace = $replace[0];
			} else {
				$replace = $this->join($replace,$join_delimiter);
			}
		}
		if ($this->has($search)) {
			$this->_replace($search,$replace);
		} else {
			throw new Exception("Could not find $search in {$this->_sql}");
		}
	}
	
	public function has($search)
	{
		return strpos($this->_sql, $search) !== false;
	}
	
	public function join($array, $delimiter=',')
	{
		return join($delimiter,$array);
	}
	
	public function append($string)
	{
		$this->_sql = $this->_sql.$string;
	}
	
	public function prepend($string)
	{
		$this->_sql = $string.$this->_sql;
	}
	
	public function render()
	{
		return $this->_sql;
	}
	
	protected function _replace($search, $replace)
	{
		$this->_sql = str_replace($search, $replace, $this->_sql);
	}
	
	public function __call($name, $args)
	{
		$this->replace($this->_prefix.$name.$this->_suffix, $args);
	}
	
	public function __toString()
	{
		return $this->render();
	}

}
