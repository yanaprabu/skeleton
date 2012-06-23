<?php
/**
 * Expression.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Sql_Expression
 *
 * Generate SQL logical expression/equation.
 *
 * @package A_Sql
 */
class A_Sql_Expression extends A_Sql_Statement
{

	protected $data = array();
	protected $operators = array('=', '!=', '>', '<', '>=', '<=', '<>', ' IN', ' NOT IN', ' LIKE', ' NOT LIKE');
	protected $db;
	protected $escape = true;
	protected $logic = ' AND ';

	public function __construct($data, $value=null, $escape=true)
	{
		if ($value !== null) {
			$this->data[$data] = $value;
		} else {
			$this->data = $data;
		}
		$this->escape = (bool) $escape;
	}

	public function setLogic($logic)
	{
		$this->logic = ' '.trim($logic).' ';
	}

	public function quoteEscape($value)
	{
		if (is_numeric($value)) {
			return $value;
		}
		$value = $this->db ? $this->db->escape($value) : addslashes($value);
		return "'" . $value . "'";
	}

	protected function buildExpression($key, $value)
	{
		if (is_int($key)) {
			$key = $value;
			$value = null;
		} else {
			$key = trim($key);
		}
		if (preg_match('@('. implode('|', $this->operators) . ')$@i', $key, $matches)) { //operator detected
			if (is_array($value)) {
				$value = '(' . implode(', ', $this->escape ? array_map(array($this, 'quoteEscape'), $value) : $value) . ')';
			} elseif ($this->escape) {
				$value = $this->quoteEscape($value);
			}
			return str_replace($matches[1], '', $key) . $matches[1] . ' ' . $value;
		} elseif ($value !== null) {
			return $key . ' = ' . ($this->escape ? $this->quoteEscape($value) : $value);
		}
		return $key;
	}

	public function render($logic='')
	{
		if (!is_array($this->data)) {
			$this->data = array($this->data);
		}
		if ($logic) {
			if ($logic == ',') {
				$logic = ', ';
			} else {
				$logic = ' ' . trim($logic) . ' ';
			}
		} else {
			$logic = $this->logic;
		}
		$sql = '';
		$exp = false;
		foreach ($this->data as $key => $value) {
			if (is_int($key) && in_array($value, array('AND', 'OR'))) {
				if ($exp) {
					$sql .= ' '.trim($value).' ';
					$exp = false;
				} else {
					echo "IGNORE $value<br/>";
				}
			} else {
				if ($exp) {
					$sql .= $logic;
				}
				$sql .= $this->buildExpression($key, $value);
				$exp = true;
			}
		}
		return $sql;
	}

	public function __toString()
	{
		return $this->render();
	}

}
