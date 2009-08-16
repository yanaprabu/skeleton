<?php
include_once 'A/Pagination/Adapter/Interface.php';

/**
 * Datasource access class for pager using ADODB  
 * 
 * @package A_Pagination 
 */

abstract class A_Pagination_Adapter_Abstract implements A_Pagination_Adapter_Interface	{

	protected $db;
	protected $query;
	protected $order_field = '';
	protected $order_descending = 0;

	public function __construct ($db, $query)	{
		$this->db = $db;
		$this->query = $query;
	}

	public function getItems ($start, $length)	{
	}

	public function getNumItems()	{
	}

	public function setOrderBy ($field, $descending = 0)	{
		$this->order_field = $field;
		$this->order_descending = ($descending === 0 ? 0 : 1);
	}

	public function constructOrderBy()	{
		if ($this->order_field)	{
			return ' ORDER BY ' . $this->order_field . ' ' . ($this->order_descending ? 'DESC' : 'ASC');
		}
	}

}