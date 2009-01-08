<?php

class A_Paginator_Collection implements A_Paginator_ICollection, Iterator, ArrayAccess	{

	private $collection = array();

	public function __construct ($collection = array())	{
		$this->collection = $collection;
	}

	public function get ($key)	{
		return $this->collection[$key];
	}

	public function add ($key, $value)	{
		if (func_num_args() == 1):
			$this->collection[] = func_get_arg (0);
		else:
			$this->collection[func_get_arg (0)] = func_get_arg (1);
		endif;
	}

	public function remove ($key)	{
		if (!$this->has ($key)) throw new Exception ('key doesn\'t exist');
		unset ($this->collection[$key]);
	}

	public function count()	{
		return count ($this->collection);
	}
	
	public function slice ($offset, $length)	{
		return new Collection (array_slice ($this->collection, $offset, $length, true));
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
	
	public function set ($key, $value)	{
		return $this->add ($key, $value);
	}
	
	public function __get ($key)	{
		return $this->get ($key);
	}
	
	public function __set ($key, $value)	{
		return $this->add ($key, $value);
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