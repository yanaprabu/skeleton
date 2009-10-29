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
		$stmt = $this->db->prepare('SELECT ' . join(', ', $this->getColumns()) . ' FROM ' . $this->table  . join(' ',$this->joins) . ' WHERE ' . $this->table . '.id = :id');
		$stmt->bindValue (':id', $id);
		$stmt->execute();
		return $this->load($stmt->fetch(PDO::FETCH_ASSOC));
	}

	public function getAll()	{
		$stmt = $this->db->prepare('SELECT ' . join(', ', $this->getColumns()) . ' FROM ' . $this->table);
		$stmt->execute();
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
		$keys = array();
		$values = array();
		foreach ($this->getValues($object) as $key => $value)	{
			$keys[] = $key . ' = ?';
			$values[] = $value;
		}
		$stmt = $this->db->prepare ('INSERT INTO ' . $this->table . ' SET ' . join(',', $keys));
		$stmt->execute($values);
		// Somehow update $object with ID
	}

	public function update($object)	{
		foreach ($this->getTableNames() as $table)	{
			$key = $this->getKey($object, $table);
			$values = $this->getValues($object, $table);
			$this->getDatasource($table)->update($values, $key);
		}
		/*
		$keys = array();
		$values = array();
		foreach ($this->getValues($object) as $key => $value)	{
			$keys[] = $key . ' = ?';
			$values[] = $value;
		}
		$pkey = $this->getKey($object);
		$values[] = current($pkey);
		
		$stmt = $this->db->prepare ('UPDATE ' . $this->table . ' SET ' . join(',', $keys) . ' WHERE ' . key($pkey) . ' = ?');
		$stmt->execute($values);
		*/
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
					return $mapping->loadArray($object);
				}
			}
		}
	}

	public function getValues($object, $table = '')	{
		$values = array();
		if (empty ($this->mappings))	{
			$values = get_object_vars($object);
		} else	{
			foreach ($this->mappings as $mapping)	{
				if (!$mapping->isKey())	{
					if (empty($table))	{
						$values = $mapping->loadArray($object, $values);
					} elseif ($mapping->getTable() == $table)	{
						$values = $mapping->loadArray($object, $values);	
					}
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
				$fields[] = array ($mapping->getAlias() => ($mapping->getTable()?$mapping->getTable().'.':'').$mapping->getColumn());
			} else 	{
				$fields[] = ($mapping->getTable()?$mapping->getTable().'.':'').$mapping->getColumn();
			}
		}
		return $fields;
	}
	
}