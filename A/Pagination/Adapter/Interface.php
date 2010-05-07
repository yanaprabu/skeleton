<?php
/**
 * Interface for pagination adapters
 * 
 * @package A_Pagination
 */

interface A_Pagination_Adapter_Interface	{

	public function getItems ($offset, $length);
	public function getNumItems();
	public function setOrderBy ($field, $descending=false);

}