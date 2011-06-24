<?php
/**
 * Parameters.php
 *
 * @package  A_Sql
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Sql_Parameters
 */
class A_Sql_Join_Parameters
{

	protected $table1;
	protected $table2;
	protected $on;
	protected $type;
	
	public function __construct($table1, $table2, $type = 'INNER')
	{
		$this->table1 = $table1;
		$this->table2 = $table2;
		$this->type = $type;
	}
	
	public function on()
	{
		$this->on = new A_Sql_LogicalList();
		$this->on->setEscape(false);
		if(func_num_args() == 1 && is_array(func_get_arg(0))) {
			
		}
		return $this;
	}
	
	public function render()
	{
		return $this->type . ' JOIN ' . $this->table1 . ($this->on ? ' ON ' . $this->on->render() : '');		
	}
	
	public function __toString()
	{
		return $this->render();
	}

}
