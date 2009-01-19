<?php

class Collection implements Iterator, ArrayAccess	{

private $collection;

function __construct ($collection = array())	{
	$this->collection = $collection;
}

function get ($key)	{
	return $this->collection[$key];
}

function add()	{
	if (func_num_args() == 1):
		$this->collection[] = func_get_arg (0);
	else:
		$this->collection[func_get_arg (0)] = func_get_arg (1);
	endif;
}

function remove ($key)	{
	if ($this->has ($key)) unset ($this->collection[$key]);
}

function count()	{
	return count ($this->collection);
}

function slice ($offset, $length)	{
	return new Collection (array_slice ($this->collection, $offset, $length, true));
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

function join ($glue)	{
	return join ($glue, $this->collection);
}

function reverse()	{
	return new Collection (array_reverse ($this->collection, true));
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