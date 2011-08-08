<?php
/**
 * Xml.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Db_Datamapper_Xml
 * 
 * Maps a database to an XML file.
 * 
 * @package A_Db
 */
class A_Db_Datamapper_Xml extends A_Db_Datamapper
{

	public function __construct($db, $filename)
	{
		$xml = simplexml_load_file($filename);
		if ($xml) {
			$this->setDb($db);
			$this->setClass(strval($xml->class));
			$this->setTable(strval($xml->table));
			foreach ($xml->mapping as $map) {
				$property = strval($map->property);
				$field = strval($map->field);
				$type = strval($map->type);
				$size = strval($map->size);
				$is_key = strval($map->is_key);
				$table = strval($map->table);
				$filters = strval($map->filters);
				$mapping = new A_Db_Datamapper_Mapping($property, $field, $type, $size, $is_key, $table, $filters);
				$this->addMapping($mapping);
			}
			if (isset($xml->join)) {
				foreach ($xml->join as $join) {
					$table1 = strval($join->table1);
					$field1 = strval($join->field1);
					$table2 = strval($join->table2);
					$field2 = strval($join->field2);
					$join_type = strval($join->join_type);
					$this->addJoin(new A_Db_Datamapper_Join($table1, $field1, $table2, $field2, $join_type));
				}
			}
		}
	}

}
