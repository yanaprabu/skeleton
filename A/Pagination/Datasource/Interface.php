<?php

interface A_Pagination_Datasource_Interface	{

	public function getItems ($offset, $length);
	public function getNumItems();

}