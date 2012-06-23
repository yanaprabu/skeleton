<?php
/**
 * Array.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Pagination_Adapter_Array
 *
 * Datasource access class for Pager using array.
 *
 * @package A_Pagination
 */
class A_Pagination_Adapter_Array implements A_Pagination_Adapter
{

	protected $data;
	protected $order_by_field = '';
	protected $order_by_descending = 0;

	public function __construct($data)
	{
		$this->data = $data;
	}

	public function getNumItems()
	{
		return count($this->data);
	}

	public function getItems($start, $length)
	{
		$max = count($this->data);
		if (($max > 0) && ($begin > 0) && ($end <= $max)) {
			--$begin;
			$this->orderBy();
			return array_slice($this->data, $begin, $end - $begin);
		}
	}

	public function setOrderBy($field, $descending=0)
	{
		$this->order_by_field = $field;
		$this->order_by_descending = $descending;
		return $this;
	}

	public function orderBy()
	{
		global $A_Pagination_Adapter_Array_Cmp_Key;
		if ($this->order_by_field) {
			$A_Pagination_Adapter_Array_Cmp_Key = $this->order_by_field;
			$reverse = ($this->order_by_descending ? '_reverse' : '');
			if (is_string($this->data[0][$A_Pagination_Adapter_Array_Cmp_Key])) {
				usort($this->data, "pageable_array_cmp_string$reverse");
			} else {
				usort($this->data, "pageable_array_cmp_number$reverse");
			}
		}
		return $this;
	}

}

$A_Pagination_Adapter_Array_Cmp_Key = '';

function pageable_array_cmp_string($a, $b)
{
	global $A_Pagination_Adapter_Array_Cmp_Key;

	return strcmp($a[$A_Pagination_Adapter_Array_Cmp_Key], $b[$A_Pagination_Adapter_Array_Cmp_Key]);
}

function pageable_array_cmp_number($a, $b)
{
	global $A_Pagination_Adapter_Array_Cmp_Key;

	return $a[$A_Pagination_Adapter_Array_Cmp_Key] > $b[$A_Pagination_Adapter_Array_Cmp_Key];
}

function pageable_array_cmp_string_reverse($a, $b)
{
	global $A_Pagination_Adapter_Array_Cmp_Key;

	return strcmp($b[$A_Pagination_Adapter_Array_Cmp_Key], $a[$A_Pagination_Adapter_Array_Cmp_Key]);
}

function pageable_array_cmp_number_reverse($a, $b)
{
	global $A_Pagination_Adapter_Array_Cmp_Key;

	return $a[$A_Pagination_Adapter_Array_Cmp_Key] < $b[$A_Pagination_Adapter_Array_Cmp_Key];
}
