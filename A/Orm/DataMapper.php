<?php
/**
 * A_Orm_DataMapper
 *
 * Implementation of Data Mapper pattern
 *
 * @author Cory Kaufman, Christopher Thompson
 * @package A_Orm
 * @version @package_version@
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
	
	public function leftJoin($table1, $table2 = '')	{
		if (!$table2)	{
			$table2 = $this->table;
		}
		return $this->addJoin(new A_Orm_DataMapper_Join($table1, $table2, 'LEFT'));
	}
	
	public function rightJoin($table1, $table2 = '')	{
		if (!$table2)	{
			$table2 = $this->table;
		}
		return $this->addJoin(new A_Orm_DataMapper_Join($table1, $table2, 'RIGHT'));
	}

	public function innerJoin($table1, $table2 = '')	{
		if (!$table2)	{
			$table2 = $this->table;
		}
		return $this->addJoin(new A_Orm_DataMapper_Join($table1, $table2, 'INNER'));
	}
	
	public function outerJoin($table1, $table2 = '')	{
		if (!$table2)	{
			$table2 = $this->table;
		}
		return $this->addJoin(new A_Orm_DataMapper_Join($table1, $table2, 'OUTER'));
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
			->columns($this->getSelectExpression())
			->from($this->getTableReferences())
			->where(array($this->table.'.id'=>$id));
# removed - what does this do?
#		foreach($this->getJoins() as $join)	{
#			$sql->join($join);
#		}
		$result = $this->db->query($sql);
		// TODO - Fix so this is not PDO specific
		return $this->load($result->fetchRow());
	}

	public function findAll()	{
		$result = $this->db->query('SELECT ' . $this->getSelectExpression() . ' FROM ' . $this->getTableReferences());
		if($result->isError())	{
			p($result->getErrorMsg());
		}
		$posts = array();
		foreach ($result->fetchAll() as $post)	{
			$posts[$post['id']] = $this->load($post);
		}
		return $posts;
	}

	public function save($object)	{
		if ($this->hasBeenPersisted($object))	{
			$this->update($object);
		} else {
			$this->insert($object);
		}
	}

	public function insert($object)	{
		foreach ($this->getTableNames() as $table)	{
			$data = $this->getData($object, $table);
			$this->db->query($this->query->insert($table)->values($data));
			$id = $this->db->lastInsertId();
		}
	}

	public function update($object)	{
		foreach ($this->getTableNames() as $table)	{
			$key = $this->getKey($object, $table);
			if($key)	{
				$data = $this->getData($object, $table);
				$this->db->query($this->query->update($table)->set($data)->where($key));	
			}
		}
	}
	
	public function delete($object)	{
		foreach ($this->getTableNames() as $table)	{
			$key = $this->getKey($object, $table);
			if($key)	{
				$this->db->query($this->query->delete($table)->where(array('id'=>$key)));
			}
		}
	}

	/*
	 * This method needs to use the mappings in order to determine which method/property is indicative of the object having been persisted -Cory
	 */
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