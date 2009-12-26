<?php

/**
 * Standard collection class that has get/set/has, iterator and array access
 * 
 * @package A 
 */
class A_Collection implements Iterator, ArrayAccess	{
	
	protected $collection;
	
	public function __construct ($collection = array())	{
		$this->collection = $collection;
	}
	
	public function get ($key)	{
		return $this->collection[$key];
	}
	
	public function add()	{
		if (func_num_args() == 1)	{
			$this->collection[] = func_get_arg (0);
		} else	{
			$this->collection[func_get_arg (0)] = func_get_arg (1);
		}
	}
	
	public function remove ($key)	{
		if ($this->has ($key)) unset ($this->collection[$key]);
	}
	
	public function count()	{
		return count ($this->collection);
	}
	
	public function slice ($offset, $length)	{
		return new A_Collection (array_slice ($this->collection, $offset, $length, true));
	}
	
	public function reverse()	{
		return new A_Collection (array_reverse ($this->collection, true));
	}
	
	public function has ($key)	{	
		return isset ($this->collection[$key]);
	}
	
	public function current()	{
		return current ($this->collection);
	}
	
	public function key()	{
		return key ($this->collection);
	}
	
	public function next()	{
		next ($this->collection);
	}
	
	public function rewind()	{
		reset ($this->collection);
	}
	
	public function valid()	{
		return current ($this->collection) !== false;
	}
	
	public function toArray()	{
		return $this->collection;
	}
	
	/*
	 * Implementation-specific methods
	 * 
	 */	 
	
	public function join ($glue)	{
		return join ($glue, $this->collection);
	}
	
	public function order ($sorter)	{
		uasort ($this->collection, array ($sorter, 'compare'));
	}
	
/*
	public function orderBy ($key, $order = 'asc')	{
		$this->order (new A_Collection_ArraySorter ($key, $order), 'compare'); 
	}
*/
	
	public function toString ($glue)	{
		return $this->join ($glue);
	}
	
	public function set ($key, $value)	{
		return $this->add ($key, $value);
	}
	
	public function __get ($key)	{
		return $this->get ($key);
	}
	
	public function __set ($key, $value)	{
		return $this->add ($key, $value);
	}
	
	public function __toString()	{
		return $this->toString (',');
	}
	
	public function offsetExists ($offset)	{
		return $this->has ($offset);
	}
	
	public function offsetGet ($offset)	{
		return $this->get ($offset);
	}
	
	public function offsetSet ($offset, $value)	{
		return $this->add ($offset, $value);
	}
	
	public function offsetUnset ($offset)	{
		return $this->remove ($offset);
	}
	
}