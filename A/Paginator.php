<?php

class A_Paginator	{

	private $collection;
	private $currentPage = 1;
	private $pageSize = 10;

	public function __construct ($collection, $pageSize)	{
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

	public function getItems()	{
		if (this->collection instanceof ICollection):
			return $this->collection->slice ($this->offset ($this->currentPage), $this->pageSize);
		elseif ($this->collection instanceof IDataContainer):
			return $this->collection->getRows ($this->offset ($this->currentPage), $this->offset ($this->currentPage) + $this->pageSize);
		endif;
	}

	public function count()	{
		if ($this->collection instanceof A_Paginator_ICollection):
			return $this->collection->count();
		elseif ($this->collection instanceof A_Pager_IDataContainer):
			return $this->collection->getNumRows();
		endif;
	}

	public function firstPage()	{
		return 1;
	}

	public function lastPage()	{
		return ceil ($this->count() / $this->pageSize);
	}

	public function isValid ($page)	{
		return ($page >= $this->firstPage() && $page <= $this->lastPage()) ? true : false; 
	}

	private function offset ($page)	{
		return (($page - 1) * $this->pageSize);
	}
		
}