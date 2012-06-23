<?php
/**
 * Core.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	Cory Kaufman, Christopher Thompson
 */

/**
 * A_Orm_DataMapper_Core
 *
 * @package A_Orm
 */
class A_Orm_DataMapper_Core
{
	protected $mappings = array();
	protected $params = array();
	protected $joins = array();
	protected $class;
	protected $table;
	protected $identityMap;

	public function __construct($db, $class, $table='', $params=array())
	{
	     $this->db = $db;
	     $this->class = $class;
	     $this->table = $table;
	     $this->params = $params;
	}

	public function setDb($db)
	{
	     $this->db = $db;
		return $this;
	}

	public function setClass($class)
	{
		$this->class = $class;
		return $this;
	}

	public function setTable($table)
	{
		$this->table = $table;
		return $this;
	}

	public function load($array, $object = null)
	{
		if (!$object) {
			$object = $this->create($this->getConstructorArguments($array));
		}
		if (empty ($this->mappings)) {
			foreach (array_keys ($array) as $column) {
				$mapping = $this->map($column);
				if ($column == 'id') {
					$mapping->setKey();
				}
			}
		}
		foreach ($this->getMappings() as $mapping) {
			$mapping->loadObject ($object, $array);
		}
		return $object;
	}

	public function update($array, $object)
	{
		if (empty($this->mappings)) {
		foreach (array_keys ($array) as $column) {
				$mapping = $this->map($column);
				if ($column == 'id') {
					$mapping->setKey();
				}
			}
		}
	}

	public function create($params=array())
	{
		if (!class_exists ($this->class)) {
			throw new Exception ('class ' . $this->class . ' does not exist.');
		}
		$class = new ReflectionClass($this->class);
		if ($class->getConstructor()) {
			return $class->newInstanceArgs($params);
		} else {
			return $class->newInstance();
		}
	}

	public function getConstructorArguments($array)
	{
		$params = array();
		foreach ($this->mappings as $mapping) {
			if ($mapping->isParam()) {
				$params[] = $mapping->getValue($array);
			}
		}
		return $params;
	}

	public function addMapping($mapping)
	{
		$this->mappings[] = $mapping;
		return $mapping;
	}

	public function map()
	{
		if (func_num_args() > 0) {
			foreach (func_get_args() as $column) {
				list($column, $table, $alias, $key) = $this->parseColumn($column);
				if (method_exists($this->class, 'get'.ucfirst($column)) && method_exists ($this->class, 'set'.ucfirst($column))) {
					$mapping = $this->mapMethods('get'.ucfirst($column), 'set'.ucfirst($column));
				} elseif (method_exists ($this->class, 'get') && method_exists ($this->class, 'set')) {
					$mapping = $this->mapGeneric($column);
				} else {
					$mapping = $this->mapProperty($column);
				}
				$mapping->toColumn(array($alias => $column), $table, $key);
			}
		}
		if (func_num_args() == 1) {
			return $mapping;
		}
	}

	public function mapMethods($getMethod, $setMethod)
	{
		return $this->addMapping(new A_Orm_DataMapper_Mapping($getMethod, $setMethod, '', '', $this->table));
	}

	public function mapGeneric($column)
	{
		list($column, $table, $alias, $key) = $this->parseColumn($column);
		return $this->addMapping(new A_Orm_DataMapper_Mapping('get', 'set', array($alias => $column), '', $table));
	}

	public function mapProperty($column)
	{
		list($column, $table, $alias, $key) = $this->parseColumn($column);
		return $this->addMapping(new A_Orm_DataMapper_Mapping('', '', $column, '', $table));
	}

	public function mapParam($column = '')
	{
		list($column, $table, $alias, $key) = $this->parseColumn($column);
		$mapping = new A_Orm_DataMapper_Mapping();
		$mapping->toColumn(array($alias => $column), $table, $key)->setParam();
		return $this->addMapping($mapping);
	}

	public function mapParams()
	{
		foreach (func_get_args() as $column) {
			$this->mapParam($column);
		}
	}

	/**
	 * Split column in "table.column:key AS alias" format into array($column, $table, $alias, $key)
	 */
	protected function parseColumn($column)
	{
		if (strpos($column, '.')) {
			list($table, $column) = explode('.', $column);
		} else {
			$table = $this->table;
		}
		$key = strpos($column, ':key');
		if ($key) {
			$column = str_replace(':key', '', $column);
		}
		if (strpos($column,' AS ')) {
			list($column, $alias) = explode(' AS ', $column);
		} else {
			$alias = '';
		}
		return array($column, $table, $alias, $key?true:false);
	}

	public function getMappings()
	{
		$mappings = array();
		foreach ($this->mappings as $mapping) {
			if ($mapping->getProperty() || $mapping->getSetMethod()) {
				$mappings[] = $mapping;
			}
		}
		return $mappings;
	}


	/**
	 * Get object from Identity Map object (if exists)
	 */
	public function get($key)
	{
		if (isset($this->identityMap)) {
			return $this->identityMap->get($key);
		}
	}

	/**
	 * Set object in Identity Map object (if exists)
	 */
	public function set($key, $object)
	{
		if (isset($this->identityMap)) {
			return $this->identityMap->get($key, $object);
		}
	}

}
