<?php

class A_Pagination_Datasource_Db implements A_Pagination_Datasource_Interface	{

	public function __construct ($db, $query)	{
		$this->db = $db;
		$this->query = $query;
	}

	public function getItems ($start, $length)	{

	}

	public function getNumItems()	{

	}

}