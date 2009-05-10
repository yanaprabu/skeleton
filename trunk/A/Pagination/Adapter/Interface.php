<?php

interface A_Pagination_Adapter_Interface	{

	public function getItems ($offset, $length);
	public function getNumItems();

}