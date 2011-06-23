<?php
/**
 * Core.php
 *
 * @package  A_Pagination
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Cory Kaufman, Christopher Thompson
 */

/**
 * A_Pagination_Core
 * 
 * Core value object to paginate items from a datasource
 */
class A_Pagination_Core
{

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
	 * @param A_Pagination_Apapter_Interface $datasource
	 * @param int $pageSize
	 * @param int $currentPage
	 */
	public function __construct(A_Pagination_Adapter_Interface $datasource, $pageSize=0, $currentPage=0)
	{
		$this->datasource = $datasource;
		if ($pageSize > 0) {
			$this->pageSize = $pageSize;
		}
		if ($currentPage > 0) {
			$this->currentPage = $currentPage;
		}
	}
	
	/**
	 * @return array All items as an array
	 */
	public function getItems()
	{
		return $this->datasource->getItems($this->getFirstItem(), $this->pageSize);
	}
	
	/**
	 * @param int $numItems Number of items in the datasource
	 * @return $this
	 */
	public function setNumItems($numItems)
	{
		$this->numItems = $numItems;
		return $this;
	}
	
	/**
	 * @return int Total number of items
	 */
	public function getNumItems()
	{
		if ($this->numItems === false) {
			$this->numItems = $this->datasource->getNumItems();
		}
		return $this->numItems;
	}
	
	/**
	 * @param integer $page Number of the last page
	 * @return $this
	 */
	public function setCurrentPage($page)
	{
		if (($page >= $this->getFirstPage()) && ($page <= $this->getLastPage())) {
			$this->currentPage = $page;
		}
		return $this;
	}
	
	/**
	 * @param int $offset Offset from current or passed page number
	 * @param int $page Allows passed page number instead of property
	 * @return mixed
	 */
	public function getPage($offset, $page = false)
	{
		if (!$page)
			$page = $this->currentPage;
		$page += $offset;
		if ($page < $this->getFirstPage()) return $this->getFirstPage();
		if ($page > $this->getLastPage())  return $this->getLastPage();
		return $page;
	}
	
	/**
	 * @return int Number of current page
	 */
	public function getCurrentPage()
	{
		return $this->currentPage;
	}
	
	/**
	 * @return int Number of first page
	 */
	public function getFirstPage()
	{
		return 1;
	}
	
	/**
	 * @return int Number of last page
	 */
	public function getLastPage()
	{
		// do we cache this value and only recalculate when getItems()/getNumItems called
		return ceil($this->getNumItems() / $this->pageSize);
	}
	
	/**
	 * Set the number of items returned by getItems()
	 * 
	 * @param int $size
	 * @return $this
	 */
	public function setPageSize($size) 
	{
		$this->pageSize = $size;
		return $this;
	}
	
	/**
	 * Set the number of page number on each side of the current page number. Total number of page numbers is $size * 2 + 1
	 * 
	 * @param int $size
	 * @return $this
	 */
	public function setRangeSize($size) 
	{
		$this->rangeSize = $size;
		return $this;
	}
	
	/**
	 * @param int $size Number of pages to offset from center
	 * @param int $page Center of range
	 * @return array Sequential page numbers
	 */
	public function getPageRange($offset=false, $page=false)
	{
		if (!$offset)
			$offset = $this->rangeSize;
		if (!$page)
			$page = $this->currentPage;
		return range($this->getPage(-$offset, $page), $this->getPage($offset, $page), 1);
	}
	
	/**
	 * @param int $page Page number to check
	 * @param int $size Number of pages in range
	 * @return boolean - true if in range, false if not
	 */
	public function inPageRange($page, $size=false)
	{
		return in_array($page, $this->getPageRange($size));
	}
	
	/**
	 * @return int Position of first item on current page
	 */
	public function getFirstItem()
	{
		return (($this->currentPage - 1) * $this->pageSize) + 1;
	}
	
	/**
	 * @return int Position of last item on current page
	 */
	public function getLastItem()
	{
		$lastItem = $this->getFirstItem() + $this->pageSize - 1;
		$numItems = $this->getNumItems();
		if ($lastItem > $numItems) {
			$lastItem = $numItems;
		}
		return $lastItem;
	}
	
	/**
	 * Check if a given page number is valid
	 * 
	 * @param int $page
	 * @return bool True if page in range of the first to last page
	 */
	public function isPage($page)
	{
		$page += $this->currentPage;
		return ($page >= $this->getFirstPage()) && ($page <= $this->getLastPage());
	}
	
	public function isIntervalPage($page)
	{
		$page += $this->currentPage;
		return ($page > $this->getFirstPage()) && ($page < $this->getLastPage());
	}
	
	/**
	 * @return bool True if number of pages > 1
	 */
	public function hasPages()
	{
		return $this->getNumItems() >= $this->pageSize;
	}
	
	public function getParamName($param)
	{
		if (isset ($this->paramNames[$param])) {
			return $this->paramNamespace . $this->paramNames[$param];
		} else {
			$this->paramNames[$param] = $param;
			return $this->paramNamespace . $param;
		}
	}
	
	public function setParamName($param, $name)
	{
		$this->paramNames[$param] = $name;
		return $this;
	}
	
	public function setParamNamespace($namespace)
	{
		$this->paramNamespace = $namespace;
	}
	
	public function setOrderBy($field, $descending=false)
	{
		$this->orderByField = $field;
		if ($descending) $this->orderByDirection = 'desc';
		$this->datasource->setOrderBy ($field, $descending);
		return $this;
	}
	
	public function getOrderBy()
	{
		return $this->orderByField;
	}
	
	public function getOrderByDirection()
	{
		return $this->orderByDirection;
	}

}
