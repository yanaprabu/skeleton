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
	protected $paramNames = array();

	/**
	 * @param
	 * @type
	 */
	public function __construct(A_Pagination_Adapter_Interface $datasource, $pageSize=0, $currentPage=0)	{
		$this->datasource = $datasource;
		if ($pageSize > 0) {
			$this->pageSize = $pageSize;
		}
		if ($currentPage > 0) {
			$this->currentPage = $currentPage;
		}
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
	 * @param integer - number of last page
	 * @type
	 */
	public function setCurrentPage($page)	{
		if (($page >= $this->getFirstPage()) && ($page <= $this->getLastPage())) {
			$this->currentPage = $page;
		}
	}

	public function getPage($page)	{
		$page += $this->currentPage;
		return ($page >= $this->getFirstPage()) && ($page <= $this->getLastPage()) ? $page : 0;
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
		return ($page >= $this->getFirstPage()) && ($page <= $this->getLastPage());
	}

	/**
	 * @param
	 * @type boolean - true if number of pages > 1
	 */
	public function hasPages()	{
		return $this->getNumItems() >= $this->pageSize;
	}

	public function getParamName ($param)	{
		if (isset ($this->paramNames[$param]))	{
			return $this->paramNames[$param];
		} else	{
			$this->paramNames[$param] = $param;
			return $param;
		}
	}

	public function setParamName ($param, $name)	{
		$this->paramNames[$param] = $name;
	}

	public function setOrderBy ($field, $descending = false)	{
		$this->datasource->setOrderBy ($field, $descending = false);
	}

}