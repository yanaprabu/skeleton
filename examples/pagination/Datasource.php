<?php
include_once 'A/Pagination/Adapter/Interface.php';

/**
 * Datasource access class for Pager using array 
 * 
 * @package A_Pager 
 */

class Datasource implements A_Pagination_Adapter_Interface {
	protected $data;
	protected $order_by_field = '';
	protected $order_by_descending = 0;
	
    public function __construct($data) {
        $this->data = $data;
    }

    public function getNumItems() { 
        return count($this->data);
    }
    
    public function getItems($start, $size) {
		$max = count($this->data);
		if (($max > 0) && ($start > 0) && ($size > 0)) {
        	$this->orderBy();
        	return array_slice($this->data, $start-1, $size);
        }
	}

    public function setOrderBy($field, $descending=0) {
		$this->order_by_field = $field;
		$this->order_by_descending = $descending;
		return $this;
    }

    public function orderBy() {
		global $A_Pager_Array_Cmp_Key;
		if ($this->order_by_field) {
			$A_Pager_Array_Cmp_Key = $this->order_by_field;
			$reverse = ($this->order_by_descending ? '_reverse' : '');
			if (is_string($this->data[0][$A_Pager_Array_Cmp_Key])) {
				usort($this->data, "pageable_array_cmp_string$reverse");
			} else {
				usort($this->data, "pageable_array_cmp_number$reverse");
			}
		}
		return $this;
    }

}

$A_Pager_Array_Cmp_Key = '';

function pageable_array_cmp_string($a, $b)
{
	global $A_Pager_Array_Cmp_Key;
	
	return strcmp($a[$A_Pager_Array_Cmp_Key], $b[$A_Pager_Array_Cmp_Key]);
}

function pageable_array_cmp_number($a, $b)
{
	global $A_Pager_Array_Cmp_Key;
	
	return $a[$A_Pager_Array_Cmp_Key] > $b[$A_Pager_Array_Cmp_Key];
}

function pageable_array_cmp_string_reverse($a, $b)
{
	global $A_Pager_Array_Cmp_Key;
	
	return strcmp($b[$A_Pager_Array_Cmp_Key], $a[$A_Pager_Array_Cmp_Key]);
}

function pageable_array_cmp_number_reverse($a, $b)
{
	global $A_Pager_Array_Cmp_Key;
	
	return $a[$A_Pager_Array_Cmp_Key] < $b[$A_Pager_Array_Cmp_Key];
}

