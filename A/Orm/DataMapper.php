<?php

/**
 * 
 * @package A_Orm
 */
class A_Orm_DataMapper	{

	protected $mappings = array();
	protected $params = array();
	protected $class;
	protected $table;

	public function __construct($db, $class, $table='', $params=array()) {
	     $this->db = $db;
	     $this->class = $class;
	     $this->table = $table;
	     $this->params = $params;
	}

	public function setDb($db) {
	     $this->db = $db;
		return $this;
	}

	public function setClass($class)	{
		$this->class = $class;
		return $this;
	}

	public function setTable($table)	{
		$this->table = $table;
		return $this;
	}

	public function load($array)	{
		$object = $this->create($this->getConstructorArguments($array));
		if (empty ($this->mappings))	{
			foreach (array_keys ($array) as $column)	{
				$mapping = $this->map($column);
				if ($column == 'id')	{
					$mapping->setKey();
				}
			}
		}
		foreach ($this->getMappings() as $mapping)	{
			$mapping->loadObject ($object, $array);
		}
		return $object;
	}

	public function create($params=array())	{
		if (!class_exists ($this->class))	{
			throw new Exception ('class ' . $this->class . ' does not exist.');
		}
		$class = new ReflectionClass($this->class);
		if ($class->getConstructor())	{
			return $class->newInstanceArgs($params);
		} else	{
			return $class->newInstance();
		}
	}

	public function getConstructorArguments($array)	{
		$params = array();
		foreach ($this->mappings as $mapping)	{
			if (!$mapping->getProperty() && !$mapping->getSetMethod())	{
				$params[] = $mapping->getValue($array);
			}
		}
		return $params;
	}
	
	public function addMapping($mapping)	{
		$this->mappings[] = $mapping;
		return $mapping;
	}

	public function addJoin($join)	{
		$this->joins[] = $join;
		return $join;
	}

	public function map($property)	{
		$mapping = $this->addMapping(new A_Orm_DataMapper_Mapping());
		if(method_exists($this->class, 'get'.ucfirst($property)) && method_exists ($this->class, 'set'.ucfirst($property)))	{
			$mapping->setGetMethod('get'.ucfirst($property));
			$mapping->setSetMethod('set'.ucfirst($property));
		}elseif(method_exists ($this->class, 'get') && method_exists ($this->class, 'set'))	{
			$mapping->setGetMethod('get');
			$mapping->setSetMethod('set');
			$mapping->setProperty($property);
		} else	{
			$mapping->setProperty($property);
		}
		$mapping->toColumn($property, $this->table);
		return $mapping;
	}

	public function mapMethods($getMethod, $setMethod)	{
		return $this->addMapping(new A_Orm_DataMapper_Mapping($getMethod, $setMethod, '', '', $this->table));
	}

	public function mapGeneric($name)	{
		return $this->addMapping(new A_Orm_DataMapper_Mapping('get', 'set', $name, '', $this->table));
	}

	public function mapProperty($property)	{
		return $this->addMapping(new A_Orm_DataMapper_Mapping('', '', $property, '', $this->table));
	}

	public function mapParam()	{
		return $this->addMapping(new A_Orm_DataMapper_Mapping('', '', '', '', $this->table));
	}

	public function join($table)	{
		return $this->addJoin(new A_Orm_DataMapper_SQLJoin($table));
	}
	
	public function leftJoin($table1, $table2)	{
		return $this->addJoin(new A_Orm_DataMapper_Join($table1, ($table2 ? $table2 : $this->table), 'LEFT'));
	}
	
	public function rightJoin($table1, $table2 = '')	{
		return $this->addJoin(new A_Orm_DataMapper_Join($table1, ($table2 ? $table2 : $this->table), 'RIGHT'));
	}

	public function innerJoin($table1, $table2 = '')	{
		return $this->addJoin(new A_Orm_DataMapper_Join($table1, ($table2 ? $table2 : $this->table), 'INNER'));
	}
	
	public function outerJoin($table1, $table2 = '')	{
		return $this->addJoin(new A_Orm_DataMapper_Join($table1, ($table2 ? $table2 : $this->table), 'OUTER'));
	}
	
	public function getMappings()	{
		$mappings = array();
		foreach ($this->mappings as $mapping)	{
			if ($mapping->getProperty() || $mapping->getSetMethod())	{
				$mappings[] = $mapping;
			}
		}
		return $mappings;
	}

}