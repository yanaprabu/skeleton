<?php

/*
 * Essentially this class is a placeholder for our eventual SQL handling capability.
 */

require_once('A/Orm/DataMapper.php');
require_once('A/Orm/DataMapper/Mapping.php');
require_once('A/Orm/DataMapper/Join.php');
require_once('A/Orm/DataMapper/SQLJoin.php');
require_once('A/Db/Tabledatagateway.php');
require_once('A/Sql/Select.php');

class HardcodedGateway extends A_Orm_DataMapper	{
	
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