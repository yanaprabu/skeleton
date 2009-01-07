<?php

class Paginator	{

private $collection;
private $page;
private $length = 10;

function __construct (Collection $collection, $page, $length)	{
	if ($collection == null) throw new Exception ('missing Collection object');
	if ($page == null) throw new Exception ('missing page');
	if ($length == null) throw new Exception ('missing length');
	$this->collection = $collection;
	$this->page = $page;
	$this->length = $length;
}

function setPage ($page)	{
	$this->page = $this->valid ($page) ? $page : $this->first();
}

function setLength ($length)	{
	$this->length = $this->valid ($length) ? $length : $this->length;
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

function valid ($page)	{
	return ($page >= $this->first() && $page <= $this->last()) ? true : false; 
}

function previous ($length = 1)	{
	$page = $this->page - $length;
	return ($this->valid ($page)) ? $page : $this->first();
}

function next ($length = 1)	{
	$page = $this->page + $length;
	return ($this->valid ($page)) ? $page : $this->last();
}

}