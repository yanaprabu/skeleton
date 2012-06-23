<?php
/**
 * Mapping.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Db_Datamapper_Mapping
 *
 * @package A_Db
 */
class A_Db_Datamapper_Mapping
{

	public $table_name = '';
	public $property_name = '';
	public $field_name = '';
	public $type = '';
	public $size = 0;
	public $is_key = false;
	public $filters = array();

	public function __construct($property_name, $field_name, $type, $size, $is_key=false, $table_name='', $filters=array())
	{
		$this->property_name = $property_name;
		$this->field_name = $field_name;
		$this->type = $type;
		$this->size = $size;
		$this->is_key = $is_key;
		$this->table_name = $table_name;
		$this->filters = $filters;
	}

	public function setObject($object, $row)
	{
	     if ($this->property_name && $this->field_name) {
	     	$property = $this->property_name;
	     	if (isset($row[$this->field_name])) {
	     		 $object->$property = $row[$this->field_name];
	     	}
	     }
		return $this;
	}

	public function setRow($row, $object)
	{
	     if ($this->property_name && $this->field_name) {
	     	$property = $this->property_name;
	     	if (isset($object->$property)) {
	     		$row[$this->field_name] = $object->$property;
	     	}
	     }
		return $this;
	}

	public function getTableFieldName()
	{
	     if ($this->table_name) {
	     	return $this->table_name . '.' . $this->field_name;
	     } else {
	     	return $this->field_name;
	     }
	}

	public function isKey()
	{
	     return $this->is_key;
	}

}
