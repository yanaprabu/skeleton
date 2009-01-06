<?php

class Paginator	{

function __construct (Collection $collection, $page, $length)	{
	if ($collection == null) throw new Exception ('missing Collection object');
	if ($page == null) throw new Exception ('missing page');
	if ($length == null) throw new Exception ('missing length');
	$this->collection = $collection;
	$this->page = $page;
	$this->length = $length;
}

function current()	{
	return $this->collection->slice ($this->offset ($this->page), $this->length);
}

function page()	{
	return $this->page;
}

function offset ($page)	{
	return (($page - 1) * $this->length);
}

function count()	{
	return $this->collection->count();
}

function first()	{
	return 1;
}

function last()	{
	return ceil ($this->count() / $this->length);
}

function valid()	{
	return $length < $this->count();
}

function previous ($length = 1)	{
	if ($length <= 0) throw new Exception ('length must be greater than 0');
	if ($this->page - $length < $this->first()) return false;
	return $this->page - $length;
	
}

function next ($length = 1)	{
	if ($length <= 0) throw new Exception ('length must be greater than 0');
	if ($this->page + $length > $this->last()) return false;
	return $this->page + $length;
}

}