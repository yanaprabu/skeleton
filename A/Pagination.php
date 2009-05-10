<?php
/**
 * A_Pagination
 *
 * Component to paginate items from a datasource
 *
 * @author Cory Kaufman, Christopher Thompson
 * @package A_Pagination
 * @version @package_version@
 */

class A_Pagination	{

	protected $datasource;
	protected $pageSize = 10;
	protected $currentPage = 1;
	protected $numPages = false;

	/**
	 * @param
	 * @type
	 */
	public function __construct(A_Pagination_Adapter_Interface $datasource, $pageSize=10, $currentPage=1)	{
		$this->datasource = $datasource;
		$this->pageSize = $pageSize;
		$this->currentPage = $currentPage;
	}

	/**
	 * @param integer - number of last page
	 * @type
	 */
	public function setCurrentPage($page)	{
		if (($page >= $this->getFirstPage()) && ($page <= $this->getLastPage())) {
			$this->currentPage = $page;
		}
	}

	/**
	 * @param
	 * @type integer - number of current page
	 */
	public function getCurrentPage()	{
		return $this->currentPage;
	}

	/**
	 * @param
	 * @type array - of items
	 */
	public function getItems()	{
		return $this->datasource->getItems($this->getFirstItem($this->currentPage), $this->pageSize);
	}

	/**
	 * @param
	 * @type integer - number of total items
	 */
	public function getNumItems()	{
		if ($this->numPages === false) {
			$this->numPages = $this->datasource->getNumItems();
		}
		return $this->numPages;
	}

	/**
	 * @param
	 * @type integer - number of last page
	 */
	public function getFirstPage()	{
		return 1;
	}

	/**
	 * @param
	 * @type integer - number of last page
	 */
	public function getLastPage()	{
		// do we cache this value and only recalculate when getItems()/getNumItems called
		return ceil($this->getNumItems() / $this->pageSize);
	}

	/**
	 * @param
	 * @type integer - position of first item on current page
	 */
	public function getFirstItem()	{
		return (($this->currentPage - 1) * $this->pageSize) + 1;
	}

	/**
	 * @param
	 * @type integer - position of last item on current page
	 */
	public function getLastItem()	{
		$lastItem = $this->getFirstItem() + $this->pageSize - 1;
		$numItems = $this->getNumItems();
		if ($lastItem > $numItems) {
			$lastItem = $numItems;
		}
		return $lastItem;
	}

	/**
	 * @param
	 * @type boolean - true if page in range of 1..last page
	 */
	public function isPage($page)	{
		$page += $this->currentPage;
		return ($page >= $this->firstPage()) && ($page <= $this->lastPage());
	}

	/**
	 * @param
	 * @type boolean - true if number of pages > 1
	 */
	public function hasPages()	{
		return $this->getNumItems() >= $this->pageSize;
	}

}