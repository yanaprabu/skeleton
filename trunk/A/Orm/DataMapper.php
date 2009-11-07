<?php

/*
 * Can we autoload these and save the calls? -Cory
 */
require_once('A/Orm/DataMapper.php');
require_once('A/Orm/DataMapper/Mapping.php');
require_once('A/Orm/DataMapper/Join.php');
require_once('A/Orm/DataMapper/SQLJoin.php');
require_once('A/Db/Tabledatagateway.php');
require_once('A/Sql/Select.php');

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

	public function load($array, $object = null)	{
		if (!$object)	{
			$object = $this->create($this->getConstructorArguments($array));	
		}
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
			if ($mapping->isParam())	{
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

	public function map()	{
		if (func_num_args() > 0)	{
			foreach(func_get_args() as $column)	{
				$mapping = $this->addMapping(new A_Orm_DataMapper_Mapping());
				list($column, $table, $alias, $key) = $this->parseColumn($column);
				if($alias)	{
					$mapping->setAlias($alias);
				}
				if($key)	{
					$mapping->isKey();
				}
				if(method_exists($this->class, 'get'.ucfirst($column)) && method_exists ($this->class, 'set'.ucfirst($column)))	{
					$mapping->setGetMethod('get'.ucfirst($column));
					$mapping->setSetMethod('set'.ucfirst($column));
				}elseif(method_exists ($this->class, 'get') && method_exists ($this->class, 'set'))	{
					$mapping->setGetMethod('get');
					$mapping->setSetMethod('set');
					$mapping->setProperty($column);
				} else	{
					$mapping->setProperty($column);
				}
				$mapping->toColumn($column, $table);
			}
		}
		if(func_num_args() == 1)	{
			return $mapping;	
		}
	}

	public function mapMethods($getMethod, $setMethod)	{
		return $this->addMapping(new A_Orm_DataMapper_Mapping($getMethod, $setMethod, '', '', $this->table));
	}

	public function mapGeneric($column)	{
		list($table, $column) = $this->parseColumnForTable($column);
		return $this->addMapping(new A_Orm_DataMapper_Mapping('get', 'set', $column, '', $table));
	}

	public function mapProperty($column)	{
		list($table, $column) = $this->parseColumnForTable($column);
		return $this->addMapping(new A_Orm_DataMapper_Mapping('', '', $column, '', $table));
	}

	public function mapParam()	{
		return $this->addMapping(new A_Orm_DataMapper_Mapping('', '', '', '', $this->table, '', '', true));
	}
	
	public function mapParams()	{
		foreach (func_get_args() as $column)	{
			if(strpos($column,':key'))	{
				$column = str_replace(':key','',$column);
				$this->mapParam()->toColumn($column)->setKey();
			} else {
				$this->mapParam()->toColumn($column);
			}
			
		}
	}

	/**
	 * Split column in "table.column:key AS alias" format into array($column, $table, $alias, $key)
	 */
	protected function parseColumn($column)	{
		if (strpos($column, '.'))	{
			list($table, $column) = explode('.', $column);
		} else {
			$table = $this->table;
		}
		$key = strpos($column, ':key');
		if ($key)	{
			$column = str_replace(':key', '', $column);
		}
		if(strpos($column,' AS '))	{
			list($column, $alias) = explode(' AS ', $column);
		} else {
			$alias = '';
		}
		return array($column, $table, $alias, $key);
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

	public function getById($id)	{
		$stmt = $this->db->prepare('SELECT ' . $this->getSelectExpression() . ' FROM ' . $this->getTableReferences() . ' WHERE ' . $this->table . '.id = :id');
		$stmt->bindValue (':id', $id);
		$stmt->execute();
		if($stmt->errorCode() != '00000')	{
			p($stmt->errorInfo());
		}
		return $this->load($stmt->fetch(PDO::FETCH_ASSOC));
	}

	public function getAll()	{
		$stmt = $this->db->prepare('SELECT ' . $this->getSelectExpression() . ' FROM ' . $this->getTableReferences());
		$stmt->execute();
		if($stmt->errorCode() != '00000')	{
			p($stmt->errorInfo());
		}
		$posts = array();
		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $post)	{
			$posts[$post['id']] = $this->load($post);
		}
		return $posts;
	}

	public function save($object)	{
		if ($user->getId())	{ // should we have a way to get the key column using mappings, rather than assuming getId() here?
			$this->update($object);
		} else {
			$this->insert($object);
		}
	}

	public function insert($object)	{
		foreach ($this->getTableNames() as $table)	{
			$data = $this->getData($object, $table);
			$this->getDatasource($table)->insert($data);
			// Somehow update $object with insert id
		}
	}

	public function update($object)	{
		foreach ($this->getTableNames() as $table)	{
			$key = $this->getKey($object, $table);
			if ($key)	{
				$data = $this->getData($object, $table);
				$this->getDatasource($table)->update($data, $key);
			}
		}
	}

	public function getDatasource($table, $key = null)	{
		if (!isset($this->gateways[$table]))	{
			$this->gateways[$table] = new A_Db_TableDataGateway($this->db, $table, $key);
		}
		return $this->gateways[$table];
	}

	public function getKey($object, $table)	{
		if (empty($this->mappings))	{
			return array('id' => $object->id);
		} else	{
			foreach ($this->mappings as $mapping)	{
				if ($mapping->isKey() && $mapping->getTable() == $table)	{
					return array($mapping->getColumn() => $mapping->getValueFromObject($object));
				}
			}
		}
	}

	public function getData($object, $table = '')	{
		$data = array();
		if (empty ($this->mappings) && is_array(get_object_vars($object)))	{
			$data = get_object_vars($object);
		} else	{
			foreach ($this->mappings as $mapping)	{
				if (!$mapping->isKey())	{
					if (empty($table))	{
						$data[$mapping->getColumn()] = $mapping->getValueFromObject($object);
					} elseif ($mapping->getTable() == $table)	{
						$data[$mapping->getColumn()] = $mapping->getValueFromObject($object);
					}
				}
			}
		}
		return $data;
	}
	
	public function getTableNames()	{
		$tables = array();
		if ($this->table) $tables[] = $this->table;
		foreach ($this->mappings as $mapping) {
			if ($mapping->getTable() && !in_array ($mapping->getTable(), $tables)) {
				$tables[] = $mapping->getTable();
			}
		}
		return $tables;
	}
	
	public function getTableReferences() {
		return join(', ', $this->getTableNames());
	}

	public function getSelectExpression()	{
		$fields = array();
		if (empty ($this->mappings))	{
			return array('*');
		}
		foreach ($this->mappings as $mapping)	{
			$field = $mapping->getColumn();
			if($mapping->getTable())	{
				$field = $mapping->getTable() . '.' . $field;
			}
			if($mapping->getAlias())	{
				$field = $field . ' AS ' . $mapping->getAlias();	
			}
			$fields[] = $field;
		}
		return join(', ', $fields);
	}
	
}