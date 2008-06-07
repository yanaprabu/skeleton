<?php
require_once 'A/Sql/Statement.php';
/**
 * A_Sql_Columns
 *
 * - Requires A_Sql_Statement
 * @license    BSD
 * @version    $Id:$
 */
class A_Sql_Select extends A_Sql_Statement {
	
	/**
	 * Rendered SQL pieces
	 * @var array
	 */
	protected $replace = array();
	
	/**
	 * Select statement pieces
	 * @var array
	 */
	protected $pieces = array(
		'tables' 	=> null,
		'columns' 	=> null,
		'joins' 	=> null,
		'where' 	=> null,
		'having' 	=> null,
		'orderby' 	=> null,
		'groupby' 	=> null,
	);

	/**
	 * Set select statement columns
	 * @return self
	 * @question Why are there no arguments inside the method declaration? 
	 * It's important both for documentation and auto-complete feature of IDE's
	 */
	public function columns() {
		require_once 'A/Sql/Columns.php';		
		$this->pieces['columns'] = new A_Sql_Columns(func_get_args());
		return $this;
	}
	
	/**
	 * Get number of columns
	 * @return int
	 */
	public function getColumns() {
		if (!$this->pieces['columns']) {
			return array();
		}
		return $this->pieces['columns']->getColumns();
	}	
	
	/**
	 * Set select statement FROM clause
	 * 
	 * @return self
	 * @question - see my notes on columns()
	 */
	public function from() {
		require_once 'A/Sql/From.php';	
		$this->pieces['tables'] = new A_Sql_From(func_get_args());
		return $this;
	}
	
	/**
	 * Set select statement WHERE clause
	 * 
	 * Succesive where invocations are added by AND
	 *
	 * @see A_Sql_Where for argument description (But you won't find anything...)
	 * @param mixed $argument1
	 * @param mixed $argument2
	 * @param mixed $argument3
	 * @return self
	 */
	public function where($argument1, $argument2=null, $argument3=null) {
		if (!$this->pieces['where']) {
			require_once 'A/Sql/Where.php';		
			$this->pieces['where'] = new A_Sql_Where();
			$this->addListener($this->pieces['where']);
		}
		$this->pieces['where']->addExpression($argument1, $argument2, $argument3);
		return $this;		
	}
    
	/**
	 * Set select statement WHERE clause by OR
	 *
	 * @param mixed $data
	 * @param string $value
	 * @return self
	 * @question Could someone elaborate on the argumetns?
	 */
	public function orWhere($data, $value=null) {
		if (!$this->pieces['where']) {
			require_once 'A/Sql/Where.php';		
			$this->pieces['where'] = new A_Sql_Where();
			$this->addListener($this->pieces['where']);
		}
		$this->pieces['where']->addExpression('OR', $data, $value);
		return $this;		
	}
	
	/**
	 * Set select statement HAVING clause
	 *
	 * Succesive having invocations are added by AND
	 * @param unknown_type $argument1
	 * @param unknown_type $argument2
	 * @param unknown_type $argument3
	 * @return self
	 * @question Please elaborate on the arguments
	 */
	public function having($argument1, $argument2=null, $argument3=null) {
		if (!$this->pieces['having']) {
			require_once 'A/Sql/Having.php';
			$this->pieces['having'] = new A_Sql_Having();
			$this->addListener($this->pieces['having']);
		}
		$this->pieces['having']->addExpression($argument1, $argument2, $argument3);
		return $this;		
	}
	
	/**
	 * Set select statement HAVING clause by OR
	 *
	 * @param unknown_type $data
	 * @param unknown_type $value
	 * @return self
	 * @question Please elaborate on the arguments
	 */
	public function orHaving($data, $value=null) {
		if (!($this->pieces['having'] instanceof A_Sql_Having)) {
			require_once 'A/Sql/Having.php';
			$this->pieces['having'] = new A_Sql_Having();
			$this->addListener($this->pieces['having']);
		}
		$this->pieces['having']->addExpression('OR', $data, $value);
		return $this;		
	}	
	
	/**
	 * Set select statement GROUP BY clause
	 *
	 * @param unknown_type $columns
	 * @return self
	 * @question all those clauses extending the columns class are too magical...
	 */
	public function groupBy($columns) {
		require_once 'A/Sql/Groupby.php';
		$this->pieces['groupby'] = new A_Sql_Groupby($columns);	
		return $this;
	}
	
	/**
	 * Set select statement ORDER BY clause
	 *
	 * @param unknown_type $columns
	 * @return self
	 * @question Same as before
	 */
	public function orderBy($columns) {
		require_once 'A/Sql/Orderby.php';
		$this->pieces['orderby'] = new A_Sql_Orderby($columns);	
		return $this;
	}
	
	/**
	 * Convert object to string, invokes render()
	 * @return string
	 */
	public function __toString() {
		return $this->render();
	}

	/**
	 * Render SQL statement from parts
	 * @return string
	 */
	public function render() {
		$this->notifyListeners();
		
		if (!($this->pieces['tables'] instanceof A_Sql_Table && count($this->pieces['tables']->getTables()))) {
			return ''; //throw new A_Sql_Exception('No valid table name was supplied');
		}

		if (!($this->pieces['columns'] instanceof A_Sql_Columns && count($this->pieces['columns']->getColumns()))) {
			$this->columns('*');
		}

		foreach ($this->pieces as $name => $piece) {
			$output = null;
			if (method_exists($piece, 'render')) {
				$output = $piece->render();
			}
			$this->replace['['.$name.']'] = strlen($output) ? ' '. $output : $output; //add spacing
		}

		$sql = "SELECT[columns][tables][joins][having][where][orderby][groupby]";
		return str_replace(array_keys($this->replace), array_values($this->replace), $sql);
	}
	
	/**
     * Clear the SQL statement parts
     *
     * @param string $part OPTIONAL
     * @return Zend_Db_Select
     */	
	public function reset() {
		foreach ($this->pieces as &$piece) {
			$piece = null;
		}
	}
}
