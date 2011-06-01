<?php
/**
 * Datamapper.php
 *
 * @package  A_Db
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Db_Datamapper
 * 
 * Basic functionality to map table columns to object fields
 */
class A_Db_Datamapper {
	protected $db = null;
	protected $class_name = '';				// class mapped by this mapper
	protected $table_name = '';				// default inherited by mappings if not specified
	protected $objects_loaded = array();	// objects associated with key and loaded with data from database
	protected $objects_added = array();		// objects with no associatetd key or data from database
	protected $mappings = array();
	protected $table_keys = array();		// array by table name of mapping objects that map table keys
	protected $joins = array();
	protected $sql = array();
	protected $allow_key_changes = true;	// allow the key to be changed in a loaded object -- insert rather than update
	protected $error = false;

	public function __construct($db, $class_name, $table_name='') {
	     $this->db = $db;
	     $this->class_name = $class_name;
	     $this->table_name = $table_name;
	}
	
	public function setDb($db) {
	     $this->db = $db;
		return $this;
	}
	
	public function setClass($class_name) {
		$this->class_name = $class_name;
		return $this;
	}
	
	public function setTable($table_name) {
		$this->table_name = $table_name;
		return $this;
	}
	
	public function addMapping($mapping) {
		$this->error = false;
		if ($mapping->property_name) {
			// use default table name if none provided
			$mapping->table_name = $mapping->table_name ? $mapping->table_name : $this->table_name;
			if ($mapping->isKey()) {
				$this->setTableKey($mapping->table_name, $mapping->field_name, $mapping->property_name);
			}
			$this->mappings[$mapping->property_name] = $mapping;
			$this->error = true;
		}
		return $this;
	}

	public function addJoin($join) {
		$this->joins[] = $join;
		$this->setTableKey($join->table1, $join->field1, '');
		$this->setTableKey($join->table2, $join->field2, '');
		return $this;
	}

	public function getTableNames() {
		$tables = array();
		if ($this->table_name) {
			$tables[$this->table_name] = 1;
		}
		foreach (array_keys($this->mappings) as $property_name) {
			if ($this->mappings[$property_name]->table_name) {
				$tables[$this->mappings[$property_name]->table_name] = 1;
			}
		}
		return array_keys($tables);
	}

	public function getTableNamesSQL() {
		$tables = $this->getTableNames();
		// more than one table then join
		if (count($tables) > 1) {
			if ($this->joins) {
				$sql = '';
				foreach (array_keys($this->joins) as $id) {
					// are both tables in join in the table array?
					if (in_array($this->joins[$id]->table1, $tables) && in_array($this->joins[$id]->table2, $tables)) {
						if (! $sql) {
							$sql = $this->joins[$id]->table1;
						}
						$sql .= $this->joins[$id]->render();
					}
					return $sql;
				}
			} else {
				return implode(',', $tables);
			}
		} else {
			return $tables[0];
		}
			
	}

	public function getTableFieldNames() {
		$fields = array();
		foreach (array_keys($this->mappings) as $property_name) {
			$fields[$property_name] = $this->mappings[$property_name]->getTableFieldName();
		}
		return $fields;
	}

	public function getKeyField($table_name='') {
		if (!$table_name) $table_name = $this->table_name;
		return isset($this->table_keys[$table_name]) ? $this->table_keys[$table_name]['field_name'] : '';
	}

	public function getKeyTableField($table_name='') {
		if (!$table_name) $table_name = $this->table_name;
		return isset($this->table_keys[$table_name]) ? $table_name . '.' . $this->table_keys[$table_name]['field_name'] : '';
	}

	public function getKeyProperty($table_name='') {
		if (!$table_name) $table_name = $this->table_name;
		return isset($this->table_keys[$table_name]) ? $this->table_keys[$table_name]['property_name'] : '';
	}

	public function getOperationSQL($field_name, $value, $operator='=') {
		if ($field_name && $value && $operator) {
			return "$field_name$operator'$value'";
		}
		return '';
	}

	public function findPropertyByField($field_name) {
		$property_name = '';
		foreach ($this->mappings as $mapping) {
			if ($field_name == $mapping->field_name) {
				 $property_name = $mapping->property_name;
				 break;
			}
		}
		return $property_name;
	}

	public function setTableKey($table_name, $field_name, $property_name) {
	if ($table_name && $field_name && ! isset($this->table_keys[$table_name])) {
			$this->table_keys[$table_name]['field_name'] = $field_name;
			if ($property_name) {
				$this->table_keys[$table_name]['property_name'] = $property_name;
			} else {
				// search mappings for property name that goes with the field name
				$property_name = $this->findPropertyByField($field_name);
				// not found in mappings, search joins
				if (! $property_name) {
					// search joins for property name that goes with the field name
					foreach ($this->joins as $join) {
						if ($field_name == $join->field1) {
							$property_name = $this->findPropertyByField($join->field2);
							 break;
						} elseif ($field_name == $join->field2) {
							$property_name = $this->findPropertyByField($join->field1);
							break;
						}
					}
				}
				$this->table_keys[$table_name]['property_name'] = $property_name;
			}
		}
		return $this;
	}
	
	public function allowKeyChanges($flag=true) {
		return $this->allow_key_changes = $flag;
	}
	
	public function isError() {
		return $this->error;
	}
	
	public function add($object, $replace=true) {
		// is same class as this mapping
		$this->error = false;
		if (get_class($object) == $this->class_name) {
			$key_property = $this->getKeyProperty();
			// replacement of loaded object allowed and has been loaded
			if (isset($this->objects_loaded[$object->$key_property])) {
				if ($replace) {
					$this->objects_loaded[$object->$key_property] = $object;
				} else {
					$this->error = true;
				}
			} else {
				$n = count($this->objects_added);
				$this->objects_added[$n] = $object;
			}
		} else {
			$this->error = true;
		}
		return $object;
	}
	
	public function load($key) {
		$this->error = false;
		// load if not already loaded
		if (! isset($this->objects_loaded[$key])) {
			$fields = $this->getTableFieldNames();
			$tables = $this->getTableNamesSQL();
			$where = $this->getOperationSQL($this->getKeyTableField(), $key);
			$row = array();
			if ($fields && $tables && $where) {
				$sql = 'SELECT ' . implode(',', $fields) . ' FROM ' . $tables . ' WHERE ' . $where;
				$result = $this->db->query($sql);	// replace this with SQL generation and fetch data
				if (! $this->db->isError()) {
					$row = $result->fetchRow();
				}
			}
			
			// record found
			if ($row) {
				$class_name = $this->class_name;			// map data to this class
				$this->objects_loaded[$key] = new $class_name ();
				// set all the object's properties that are mapped tos database fields
				foreach ($this->mappings as $property_name => $mapping) {
					$mapping->setObject($this->objects_loaded[$key], $row);
					if (isset($row[$mapping->field_name])) {
						$this->dirty[$key][$property_name] = crc32($row[$mapping->field_name]);
					}
				}
			} else {
				$this->error = true;
			}
		}
		return $this->objects_loaded[$key];
	}
	
	public function render() {

		// update objects that have been added
		if ($this->objects_loaded) {
			$key_property = $this->getKeyProperty();
			$key_field = $this->getKeyField();
			$data = array();
			foreach ($this->objects_loaded as $key => $object) {
				if ($object) {
					foreach ($this->dirty[$key] as $property_name => $crc) {
						// check each property to see if it has changed
						if (crc32($object->$property_name) != $crc) {
							// check each the value of a key in a loaded record has changed
							if ($property_name == $key_property) {
								if ($this->allow_key_changes) {
									$this->add($object);
									$this->objects_loaded[$key] = null;
								} else {
									// change of key not allowed
								}
								$data = array();
								break;
							} else {
								// add [table_name][key][field_name] = value
								$table_name = $this->mappings[$property_name]->table_name;
								$key_property = $this->table_keys[$table_name]['property_name'];
								$data[$table_name][$object->$key_property][$this->mappings[$property_name]->field_name] = $object->$property_name;
							}
						} else {
							// field has not changed -- no update requireds 
						}
					}
				} else {
					// obeject has been deleted
				}
			}
			if ($data) {
				foreach ($data as $table_name => $d) {
					$key_field = $this->table_keys[$table_name]['field_name'];
					foreach ($d as $key => $row) {
						$row[$key_field] = $key;
						$this->updateSQL($table_name, $key_field, $row);
					}
				}
			}
		}
		// insert objects that have been added
		if ($this->objects_added) {
			foreach ($this->objects_added as $object) {
				if ($object) {
					$data = array();
					// build a field => value array
					foreach ($this->mappings as $property_name => $mapping) {
						$data[$this->mappings[$property_name]->table_name][$this->mappings[$property_name]->field_name] = $object->$property_name;
					}
					foreach ($data as $table_name => $row) {
						// check if key field is set -- required for insert
						if (! isset($row[$this->table_keys[$table_name]['field_name']])) {
							// get property name for this table's key
							$property_name = $this->table_keys[$table_name]['property_name'];
							// set table key from value in object
							$row[$this->table_keys[$table_name]['field_name']] = $object->$property_name;
						}
						$this->insertSQL($table_name, $row);
					}
				}
			}
		}
	}
	
	public function updateSQL($table, $key, $data) {
		if ($table && $key && $data) {
			if (isset($data[$key])) {
				$key_value = $data[$key];
				unset($data[$key]);
				foreach ($data as $field => $value) {
					$sets[] = $field . "='" . $this->db->escape($value) . "'";
				}
				$this->sql[] = "UPDATE $table SET " . implode(',', $sets) . " WHERE $key='$key_value';";
			}
		}
	}
	
	public function insertSQL($table_name, $data) {
		if ($data) {
			foreach ($data as $field => $value) {
				$cols[] = $field;
				$values[] = $this->db->escape($value);
			}
			$this->sql[] = "INSERT INTO $table_name (" . implode(',', $cols) . ") VALUES ('" . implode("','", $values) . "');";
		}
	}
	
	public function deleteSQL($table_name, $key, $key_value) {
		if ($key) {
			$this->sql[] = "DELETE FROM $table_name WHERE $key='$key_value';";
		}
	}
	
	public function commit() {
		if ($this->db) {
			// generate SQL for each update/insert/delete
			$this->render();
			if ($this->sql) {
				foreach ($this->sql as $sql) {
					$this->db->query($sql);
				}
			}
		}
	}
	
}

class A_Db_Datamapper_Mapping {
	public $table_name = '';
	public $property_name = '';
	public $field_name = '';
	public $type = '';
	public $size = 0;
	public $is_key = false;
	public $filters = array();

	public function __construct($property_name, $field_name, $type, $size, $is_key=false, $table_name='', $filters=array()) {
		$this->property_name = $property_name;
		$this->field_name = $field_name;
		$this->type = $type;
		$this->size = $size;
		$this->is_key = $is_key;
		$this->table_name = $table_name;
		$this->filters = $filters;
	}
	
	public function setObject($object, $row) {
	     if ($this->property_name && $this->field_name) {
	     	$property = $this->property_name;
	     	if (isset($row[$this->field_name])) {
	     		 $object->$property = $row[$this->field_name];
	     	}
	     }
		return $this;
	}
	
	public function setRow($row, $object) {
	     if ($this->property_name && $this->field_name) {
	     	$property = $this->property_name;
	     	if (isset($object->$property)) {
	     		$row[$this->field_name] = $object->$property;
	     	}
	     }
		return $this;
	}

	public function getTableFieldName() {
	     if ($this->table_name) {
	     	return $this->table_name . '.' . $this->field_name;
	     } else {
	     	return $this->field_name;
	     }
	}

	public function isKey() {
	     return $this->is_key;
	}

}

class A_Db_Datamapper_Join {
	public $table1; 
	public $field1;
	public $table2; 
	public $field2;
	public $type;
	
	function __construct($table1, $field1, $table2, $field2, $type='') {
		$this->table1 = $table1; 
		$this->field1 = $field1;
		$this->table2 = $table2; 
		$this->field2 = $field2;
		$this->type = $type;
	}

	function render() {
		return " {$this->table1} {$this->type} JOIN {$this->table2} ON {$this->table1}.{$this->field1}={$this->table2}.{$this->field2} ";
	}
}

