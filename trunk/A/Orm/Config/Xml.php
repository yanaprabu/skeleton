<?php
/**
 * Xml.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Orm_Config_Xml
 *
 * @package A_Orm
 */
class A_Orm_Config_Xml extends A_Orm_Datamapper
{
	protected $data = array();

	public function __construct($filename)
	{
		$this->loadFile($filename);
	}

	public function loadFile($filename)
	{
		$this->data = array();
		$xml = simplexml_load_file($filename);
		if ($xml) {
			$this->data['class'] = strval($xml->class);
			$this->data['table'] = strval($xml->table);
			$n = 0;
			foreach ($xml->mapping as $map) {
				$property = strval($map->property);
				$field = strval($map->field);
				$type = strval($map->type);
				$size = strval($map->size);
				$is_key = strval($map->is_key);
				$table = strval($map->table);
				$filters = strval($map->filters);
				$this->data['mappings'][$n++] = array(
					'property' => $property,
					'field' => $field,
					'type' => $type,
					'size' => $size,
					'is_key' => $is_key,
					'table' => $table,
					'filters' => $filters,
					);
#				$mapping = new A_Orm_Datamapper_Mapping($property, $field, $type, $size, $is_key, $table, $filters);
#				$this->addMapping($mapping);
			}
			if (isset($xml->join)) {
				$n = 0;
				foreach ($xml->join as $join) {
					$table = strval($join->table);
					$on = strval($join->on);
					$type = strval($join->type);
					$this->data['joins'][$n++] = array(
						'table' => $table,
						'on' => $on,
						'type' => $type,
					);
#					$this->addJoin(new A_Orm_Datamapper_Join($table1, $on, $type));
				}
			}
		}
	}

}
