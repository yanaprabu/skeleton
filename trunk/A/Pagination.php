<?php

class A_Pagination	{

	protected $datasource;
	protected $pageSize = 10;
	protected $currentPage = 1;

	public function __construct(A_Pagination_Datasource_Interface $datasource, $pageSize=10, $currentPage=1)	{
		$this->datasource = $datasource;
		$this->pageSize = $pageSize;
		$this->currentPage = $currentPage;
	}

	public function setCurrentPage($currentPage)	{
		$this->currentPage = $this->isValid($currentPage) ? $currentPage : $this->currentPage;
	}

	public function getCurrentPage()	{
		return $this->currentPage;
	}

	// should this just be called items() because we don't use get*() on most methods
	public function getItems()	{
		return $this->datasource->getItems($this->getOffset($this->currentPage), $this->pageSize);
	}

	// should this just be called numItems() because we don't use get*() on most methods
	public function getNumItems()	{
		// should we cache this value because it may be expensive for datasource to get this value?
		return $this->datasource->getNumItems();
	}

	public function getFirstPage()	{
		return 1;
	}

	public function getLastPage()	{
		// do we cache this value and only recalculate when getItems()/getNumItems called
		return ceil($this->getNumItems() / $this->pageSize);
	}

	public function getFirstItem()	{
		return $this->getOffset($this->currentPage);
	}

	public function getLastItem()	{
		$lastItem = $this->getOffset($this->currentPage) + $this->pageSize - 1;
		$numItems = $this->getNumItems();
		if ($lastItem > $numItems) {
			$lastItem = $numItems;
		}
		return $lastItem;
	}

	public function isValid($page)	{
		return ($page >= $this->firstPage() && $page <= $this->lastPage()) ? true : false;
	}

	protected function getOffset($page)	{
		// Should this be put in firstItem($page=0)? If 0 then use $this->currentPage.
		// Also the isValid check could be skip for the currentPage.
		if ($this->isValid($page)) {
			return (($page - 1) * $this->pageSize) + 1;
		}
	}

}