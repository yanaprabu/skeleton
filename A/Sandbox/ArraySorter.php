<?php

class A_Collection_ArraySorter	{

	private $key;
	private $order;

	function __construct ($key, $order = 'asc')	{
		$this->key = $key;
		$this->order = $order;
	}

	function compare ($a, $b)	{
		if (is_string ($a) || is_string ($b))	{
			if ($this->order == 'asc') return strcmp ($a[$this->key], $b[$this->key]);
			else return strcomp ($b[$this->key], $a[$this->key]);
		} else {
			if ($this->order == 'asc') return $a[$this->key] > $b[$this->key];
			else return $a[$this->key] < $b[$this->key];
		}
	}
	
}