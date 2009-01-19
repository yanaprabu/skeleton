<?php

class Paginator	{

	private $collection;
	private $currentPage = 1;
	private $pageSize = 10;

	public function __construct (Collection $collection, $pageSize)	{
		if ($collection == null) throw new Exception ('Must supply valid Collection');
		if ($pageSize == null) throw new Exception ('Must supply valid page size');
		$this->collection = $collection;
		$this->pageSize = $pageSize;
	}

	public function setCurrentPage ($currentPage)	{
		$this->currentPage = $this->isValid ($currentPage) ? $currentPage : $this->currentPage;
	}

	public function currentPage()	{
		return $this->page;
	}

	public function getCollection()	{
		return $this->collection->slice ($this->offset ($this->currentPage), $this->pageSize);
	}

	public function count()	{
		return $this->collection->count();
	}

	public function firstPage()	{
		return 1;
	}

	public function lastPage()	{
		return ceil ($this->count() / $this->pageSize);
	}

	public function firstItem()	{
		return $this->offset ($this->currentPage) + 1;
	}
	
	public function lastItem()	{
		return $this->offset ($this->currentPage) + $this->pageSize > $this->count() ? $this->count() : $this->offset ($this->currentPage) + $this->pageSize;
	}
	
	public function isValid ($page)	{
		return ($page >= $this->firstPage() && $page <= $this->lastPage()) ? true : false; 
	}

	private function offset ($page)	{
		return (($page - 1) * $this->pageSize);
	}
	
	
}