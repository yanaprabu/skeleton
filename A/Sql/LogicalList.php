<?php
#include_once 'A/Sql/Statement.php';
/**
 * LogicalList.php
 *
 * @package  A_Sql
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Sql_LogicalList
 * 
 * Generate SQL AND/OR/NOT list strings.
 */
class A_Sql_LogicalList extends A_Sql_Statement {
	protected $data = array();
	protected $escape = true;

	public function setEscape($escape) {
		$this->escape = (bool)$escape;
	} 

	public function clear() {
		$this->data = array();
	} 

	public function addExpression($arg1, $arg2=null, $arg3=null) {
		if ($arg1) {	
			$logic = 'AND';		// if 3rd null and arg2 not an array then it is ('field', 'value')
#			if ($arg3 === null && !is_array($arg2)) {
#				$logic = 'AND';		// if 3rd null and arg2 not an array then it is ('field', 'value')
#			} else {
			if (is_array($arg2) || ($arg2 && $arg3)) {
				$logic = $arg1;		// if 3rd arg is set then it is logic
				$arg1  = $arg2;		// move args down
				$arg2  = $arg3;
			}
			$expression = new A_Sql_Expression($arg1, $arg2, $this->escape);
			$this->escapeListeners[] = $expression;
			// if there are exiting expressions then add logic string to array when adding an expression
			if (count($this->data)) {
				$this->data[] = $logic;
	        }
			$this->data[] = $expression;
		}    
        return $this;
	}
	
	
	public function render() {
		if (!count($this->data)) return;
		
		$this->notifyListeners();

		$output = array();
		foreach ($this->data as $data) {
			$output[] = is_object($data) ? '('. $data->render() .')' : strtoupper($data);	// will alternate exp, logic, exp, logic, etc.
		}
		return implode(' ', $output);
	}
}