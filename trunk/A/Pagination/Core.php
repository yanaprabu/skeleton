<?php
/**
 * A_Pagination_Core
 *
 * Core value object to paginate items from a datasource
 *
 * @author Kaufman, Christopher Thompson
 * @package A_Pagination
 * @version @package_version@
 */

class A_Pagination_Core	{

	protected $datasource;
	protected $pageSize = 10;				// number of items per page
	protected $currentPage = 1;
	protected $numItems = false;
	protected $numPages = false;
	protected $rangeSize = 4;				// size of range either side of current page
	protected $paramNames = array();
	protected $paramNamespace = '';
	protected $orderByField = '';
	protected $orderByDirection = 'asc';

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
		return $this->datasource->getItems($this->getFirstItem(), $this->pageSize);
	}

	/**
	 * @param
	 * @type integer - number of total items
	 */
	public function setNumItems($numItems)	{
		$this->numItems = $numItems;
		return $this;
	}

	/**
	 * @param
	 * @type integer - number of total items
	 */
	public function getNumItems()	{
		if ($this->numItems === false) {
			$this->numItems = $this->datasource->getNumItems();
		}
		return $this->numItems;
	}

	/**
	 * @param integer - number of last page
	 * @type
	 */
	public function setCurrentPage($page)	{
		if (($page >= $this->getFirstPage()) && ($page <= $this->getLastPage())) {
			$this->currentPage = $page;
		}
		return $this;
	}

	public function getPage($offset, $page = false)	{
		if (!$page) $page = $this->currentPage;
		$page += $offset;
		if ($page < $this->getFirstPage()) return $this->getFirstPage();
		if ($page > $this->getLastPage())  return $this->getLastPage();
		return $page;
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

	public function setRangeSize($size)  {
		$this->rangeSize = $size;
		return $this;
	}

	/**
	 * @param $size - number of pages to offset from center
	 * @param $page - center of range
	 * @type array - of sequential page numbers
	 */
	public function getPageRange($offset=false, $page=false)	{
		if (!$offset) $offset = $this->rangeSize;
		if (!$page) $page = $this->currentPage;
		return range($this->getPage(-$offset, $page), $this->getPage($offset, $page), 1);
	}

	/**
	 * @param $page - page number to check
	 * @param $start - offset relative to current page
	 * @param $size - number of pages in range
	 * @type boolean - true if in range, false if not
	 */
	public function inPageRange ($page, $size=false)	{
		return in_array ($page, $this->getPageRange ($size));
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

	public function isIntervalPage ($page)	{
		$page += $this->currentPage;
		return ($page > $this->getFirstPage()) && ($page < $this->getLastPage());
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
			return $this->paramNamespace . $this->paramNames[$param];
		} else	{
			$this->paramNames[$param] = $param;
			return $this->paramNamespace . $param;
		}
	}

	public function setParamName ($param, $name)	{
		$this->paramNames[$param] = $name;
		return $this;
	}

	public function setParamNamespace($namespace)	{
		$this->paramNamespace = $namespace;
	}

	public function setOrderBy ($field, $descending = false)	{
		$this->orderByField = $field;
		if ($descending) $this->orderByDirection = 'desc';
		$this->datasource->setOrderBy ($field, $descending);
		return $this;
	}

	public function getOrderBy()	{
		return $this->orderByField;
	}

	public function getOrderByDirection()	{
		return $this->orderByDirection;
	}

}