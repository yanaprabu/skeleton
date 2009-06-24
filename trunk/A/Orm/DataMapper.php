<?php

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
		$object = $this->create($this->getParams($array));
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

	public function getParams($array)	{
		$params = array();
		foreach ($this->mappings as $mapping)	{
			if (!$mapping->getProperty() && !$mapping->getSetMethod())	{
				$params[] = $mapping->getValue($array);
			}
		}
		return $params;
	}

	public function map($property)	{
		$mapping = new A_Orm_DataMapper_Mapping();
		$this->mappings[] = $mapping;
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
		$mapping = new A_Orm_DataMapper_Mapping($getMethod, $setMethod, '', '', $this->table);
		$this->mappings[] = $mapping;
		return $mapping;
	}

	public function mapProperty($property)	{
		$mapping = new A_Orm_DataMapper_Mapping('', '', $property, '', $this->table);
		$this->mappings[] = $mapping;
		return $mapping;
	}

	public function mapGeneric($name)	{
		$mapping = new A_Orm_DataMapper_Mapping('get', 'set', $name, '', $this->table);
		$this->mappings[] = $mapping;
		return $mapping;
	}

	public function mapParam()	{
		$mapping = new A_Orm_DataMapper_Mapping();
		$mapping->setTable($this->table);
		$this->mappings[] = $mapping;
		return $mapping;
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

	public function getKey($object)	{
		$key = array();
		if (empty ($this->mappings))	{
			$key['id'] = $object->id;
		} else	{
			foreach ($this->mappings as $mapping)	{
				if ($mapping->isKey())	{
					$key = $mapping->loadArray($object, $values);
				}
			}
		}
		return $key;
	}

	public function getValues($object)	{
		$values = array();
		if (empty ($this->mappings))	{
			$values = get_object_vars($object);
		} else	{
			foreach ($this->mappings as $mapping)	{
				if (!$mapping->isKey())	{
					$values = $mapping->loadArray($object, $values);
				}
			}
		}
		return $values ? $values : array();
	}

	public function getTableNames() {
		$tables = array();
		if ($this->table) $tables[] = $this->table;
		foreach ($this->mappings as $mapping) {
			if ($mapping->getTable() && !in_array ($mapping->getTable(), $tables)) {
				$tables[] = $mapping->getTable();
			}
		}
		return $tables;
	}

	public function getColumns()	{
		$fields = array();
		if (empty ($this->mappings))	{
			return array('*');
		}
		foreach ($this->mappings as $mapping)	{
			if ($mapping->getAlias())	{
				$fields[] = array ($mapping->getAlias() => $mapping->getColumn());
			} else 	{
				$fields[] = $mapping->getColumn();
			}
		}
		return $fields;
	}

}