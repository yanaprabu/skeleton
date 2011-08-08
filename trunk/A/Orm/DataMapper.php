<?php
/**
 * DataMapper.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	Cory Kaufman, Christopher Thompson
 */

/**
 * A_Orm_DataMapper
 *
 * Implementation of Data Mapper pattern
 * 
 * @package A_Orm
 */
class A_Orm_DataMapper extends A_Orm_DataMapper_Core	{

	public function __construct($db, $class, $table ='', $params = array())	{
		parent::__construct($db, $class, $table, $params);
		$this->query = new A_Sql_Query();
	}
		
	protected function addJoin($join)	{
		$this->joins[] = $join;
		return $join;
	}
	
	protected function getJoins()	{
		return $this->joins;
	}
	
	public function join($table)	{
		return $this->addJoin(new A_Orm_DataMapper_SQLJoin($table));
	}
	
	public function leftJoin($table, $on = '')	{
		return $this->addJoin(new A_Orm_DataMapper_Join($table, $on, 'LEFT'));
	}
	
	public function rightJoin($table, $on = '')	{
		return $this->addJoin(new A_Orm_DataMapper_Join($table, $on, 'RIGHT'));
	}

	public function innerJoin($table, $on = '')	{
		return $this->addJoin(new A_Orm_DataMapper_Join($table, $on, 'INNER'));
	}
	
	public function outerJoin($table, $on = '')	{
		return $this->addJoin(new A_Orm_DataMapper_Join($table, $on, 'OUTER'));
	}
	
	public function find()	{
		if (func_num_args() == 1)	{
			return $this->findById(func_get_arg(0));
		} else	{
			$args = func_get_args();
			return call_user_func_array(array($this, 'findAll'), $args);
		}
	}
	
	public function findById($id)	{
		$sql = $this->query->select()
			->columns($this->getColumns())
			->from($this->getTables())
			->where(array($this->table.'.id'=>$id));
		$result = $this->db->query($sql);
		return $this->load($result->fetchRow());
	}

	public function findAll()	{
		$sql = $this->query->select()
			->columns($this->getColumns())
			->from($this->getTables());
		$result = $this->db->query($sql);
		$items = array();
		// TODO This section shouldn't rely on $item['id'] being the primary key
		foreach ($result->fetchAll() as $item)	{
			$items[$item['id']] = $this->load($post);
		}
		return $items;
	}

	public function save($object)	{
		if ($this->hasBeenPersisted($object))	{
			$this->update($object);
		} else {
			$this->insert($object);
		}
	}

	public function insert($object)	{
		foreach ($this->getTables() as $table)	{
			$sql = $this->query->insert($table)->values($this->getData($object, $table));
			$this->db->query($sql);
			$id = $this->db->lastInsertId();
		}
	}

	public function update($object)	{
		foreach ($this->getTables() as $table)	{
			$key = $this->getKey($object, $table);
			if($key)	{
				$values = $this->getData($object, $table);
				$sql = $this->query->update($table)->set($values)->where($key);
				$this->db->query($sql);
			}
		}
	}
	
	public function delete($object)	{
		foreach ($this->getTableNames() as $table)	{
			$key = $this->getKey($object, $table);
			if($key)	{
				$sql = $this->query->delete($table)->where($key);
				$this->db->query($sql);
			}
		}
	}

	public function hasBeenPersisted($object)	{
		if($object->getId())	{
			return true;
		}
		return false;
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
	
	public function getTables()	{
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
		$columns = array();
		if (empty ($this->mappings))	{
			return array('*');
		}
		foreach ($this->mappings as $mapping)	{
			$column = $mapping->getColumn();
			if($mapping->getTable())	{
				$column = $mapping->getTable() . '.' . $column;
			}
			if($mapping->getAlias())	{
				$column = $column . ' AS ' . $mapping->getAlias();	
			}
			$columns[] = $column;
		}
		return $columns;
	}
	
}
