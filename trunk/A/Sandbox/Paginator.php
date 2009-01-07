<?php

class Paginator	{

private $collection;
private $page = 1;
private $pageSize = 10;

function __construct (Collection $collection)	{
	if ($collection == null) throw new Exception ('Must supply valid Collection');
	$this->collection = $collection;
}

function setPageSize ($pageSize)	{
	$this->pageSize = $pageSize;
}

function setCurrentPage ($page)	{
	if (!isset ($this->pageSize)) throw new Exception ('Must call setPageSize() before calling setCurrentPage()');
	$this->page = $this->validPage ($page) ? $page : $this->firstPage();
}

function current()	{
	return $this->collection->slice ($this->offset ($this->page), $this->pageSize);
}

function page()	{
	return $this->page;
}

function offset ($page)	{
	return (($page - 1) * $this->pageSize);
}

function count()	{
	return $this->collection->count();
}

function firstPage()	{
	return 1;
}

function lastPage()	{
	return ceil ($this->count() / $this->pageSize);
}

function validPage ($page)	{
	return ($page >= $this->firstPage() && $page <= $this->lastPage()) ? true : false; 
}

function previous ($length = 1)	{
	$page = $this->page - $length;
	return ($this->validPage ($page)) ? $page : $this->firstPage();
}

function next ($length = 1)	{
	$page = $this->page + $length;
	return ($this->validPage ($page)) ? $page : $this->lastPage();
}

}