<?php

class A_Collection implements Iterator, ArrayAccess	{
	
	private $collection;
	
	function __construct ($collection = array())	{
		$this->collection = $collection;
	}
	
	function get ($key)	{
		return $this->collection[$key];
	}
	
	function add()	{
		if (func_num_args() == 1)	{
			$this->collection[] = func_get_arg (0);
		} else	{
			$this->collection[func_get_arg (0)] = func_get_arg (1);
		}
	}
	
	function remove ($key)	{
		if ($this->has ($key)) unset ($this->collection[$key]);
	}
	
	function count()	{
		return count ($this->collection);
	}
	
	function slice ($offset, $length)	{
		return new A_Collection (array_slice ($this->collection, $offset, $length, true));
	}
	
	function reverse()	{
		return new A_Collection (array_reverse ($this->collection, true));
	}
	
	function has ($key)	{	
		return isset ($this->collection[$key]);
	}
	
	function current()	{
		return current ($this->collection);
	}
	
	function key()	{
		return key ($this->collection);
	}
	
	function next()	{
		next ($this->collection);
	}
	
	function rewind()	{
		reset ($this->collection);
	}
	
	function valid()	{
		return current ($this->collection) !== false;
	}
	
	function toArray()	{
		return $this->collection;
	}
	
	/*
	 * Implementation-specific methods
	 * 
	 */	 
	
	function join ($glue)	{
		return join ($glue, $this->collection);
	}
	
	function order ($sorter)	{
		uasort ($this->collection, array ($sorter, 'compare'));
	}
	
	function orderBy ($key, $order = 'asc')	{
		$this->order (new A_Collection_ArraySorter ($key, $order), 'compare'); 
	}
	
	function toString ($glue)	{
		return $this->join ($glue);
	}
	
	function set ($key, $value)	{
		return $this->add ($key, $value);
	}
	
	function __get ($key)	{
		return $this->get ($key);
	}
	
	function __set ($key, $value)	{
		return $this->add ($key, $value);
	}
	
	function __toString()	{
		return $this->toString (',');
	}
	
	function offsetExists ($offset)	{
		return $this->has ($offset);
	}
	
	function offsetGet ($offset)	{
		return $this->get ($offset);
	}
	
	function offsetSet ($offset, $value)	{
		return $this->add ($offset, $value);
	}
	
	function offsetUnset ($offset)	{
		return $this->remove ($offset);
	}
	
	}