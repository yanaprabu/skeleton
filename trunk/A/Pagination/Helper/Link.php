<?php
/**
 * Link.php
 *
 * @package  A_Pagination
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Cory Kaufman, Christopher Thompson
 */

/**
 * A_Pagination_Helper_Link
 * 
 * Genreate HTML links
 */
class A_Pagination_Helper_Link
{

	public $url;
	
	protected $pager;
	protected $class = false;
	protected $separator = ' ';
	protected $alwaysShowFirstLast = false;
	protected $alwaysShowPreviousNext = false;
	
	/**
	 * @param A_Pagination_Core $pager
	 * @param A_Pagination_Helper_Url $url Will be created if not passed. 
	 */
	public function __construct($pager, $url=false)
	{
		$this->pager = $pager;
		$this->url = $url ? $url : new A_Pagination_Helper_Url();
		$page = $this->pager->getCurrentPage();
		if ($page > 1) {
			$this->url->set($this->pager->getParamName('page'), $page);
		}
		$num_items = $this->pager->getNumItems();
		if ($num_items) {
			$this->url->set($this->pager->getParamName('num_items'), $num_items);
		}
		$order_by = $this->pager->getOrderBy();
		if ($order_by) {
			$this->url->set($this->pager->getParamName('order_by'), $order_by);
		}
	}
	
	/**
	 * @param string $separator Text to put between links
	 * @return $this
	 */
	public function setSeparator($separator)
	{
		$this->separator = $separator;
		return $this;
	}
	
	/**
	 * @param string $class Class name
	 * @return $this
	 */
	public function setClass($class)
	{
		$this->class = $class;
		return $this;
	}
	
	/**
	 *  Set whether first/last links are shown
	 * 
	 * @param bool $always
	 * @return $this
	 */
	public function alwaysShowFirstLast($always=true)
	{
		$this->alwaysShowFirstLast = $always;
		return $this;
	}
	
	/**
	 * Set whether next/prev links are shown
	 * 
	 * @param bool $always
	 * @return $this
	 */
	public function alwaysShowPreviousNext($always=true)
	{
		$this->alwaysShowPreviousNext = $always;
		return $this;
	}
	
	/**
	 * @param string $label Text for link
 	 * @param string $separator Text to put after link
	 * @return string HTML link or '' if not in range
	 */
	public function first($label=false, $separator=true)
	{
		$page = $this->pager->getFirstPage();
		if (!$this->pager->inPageRange($page) || $this->alwaysShowFirstLast == true) {
			return $this->page($page, $label) . ($separator ? $this->separator : '');
		}
		return '';
	}
	
	/**
	 * @param string $label Text for link
 	 * @param string $separator Text to put after link
	 * @return string HTML link or '' if not in range
	 */
	public function previous ($label=false, $separator=true)
	{
		if ($this->pager->isPage(-1) || $this->alwaysShowPreviousNext == true) {
			return $this->page($this->pager->getPage(-1), $label) . ($separator ? $this->separator : '');
		}
		return '';
	}
	
	/**
	 * @param int $page Page number
	 * @param string $label Text label to use instead of page number
	 * @return string HTML link or '' if not in range
	 */
	public function page($page=false, $label=false)
	{
		return $this->_link($this->url->render(false, array($this->pager->getParamName('page') => $page)), $label ? $label : $page);
	}
	
	/**
	 * @param string $label Text for link
 	 * @param string $separator Text to put after link
	 * @return string HTML link or '' if not in range
	 */
	public function next($label=false, $separator=true)
	{
		if ($this->pager->isPage(+1) || $this->alwaysShowPreviousNext == true) {
			return ($separator ? $this->separator : '') . $this->page($this->pager->getPage(+1), $label);
		}
		return '';
	}
	
	/**
	 * @param string $label Text for link
 	 * @param string $separator Text to put after link
	 * @return string HTML link or '' if not in range
	 */
	public function last($label=false, $separator=true) {
		$page = $this->pager->getLastPage();
		if (!$this->pager->inPageRange($page) || $this->alwaysShowFirstLast == true) {
			return ($separator ? $this->separator : '') . $this->page($page, $label);
		}
		return '';
	}
	
	/**
	 * @param int $offset
	 * @param int $page
	 * @return array HTML link strings
	 */
	public function range($offset=false, $page=false)
	{
		$links = array();
		$current_page = $this->pager->getCurrentPage();
		foreach ($this->pager->getPageRange($offset, $page) as $n) {
			if ($n != $current_page) {
				$links[] = $this->page($n);
			} else {
				$links[] = $n;
			}
		}
		return implode($this->separator, $links);
	}
	
	/**
	 * @param string $field
	 * @param string $label
	 * @param bool $descending
	 * @return
	 */
	public function order($field, $label='', $descending = null) {
		// if descending not specified the currently sorting on this field then reverse sort
		if (($descending === null) && ($field == $this->pager->getOrderBy())) {
			$descending = $this->pager->getOrderByDirection() == 'desc' ? false : true;
		}
		$orderBy = $field . ($descending ? '|desc' : '');
		return $this->_link($this->url->render(false, array($this->pager->getParamName('order_by') => $orderBy), array($this->pager->getParamName('page'))), $label ? $label : $page);
	}
	
	/**
	 * @return string Value of the separator property
	 */
	public function separator()
	{
		return $this->separator;
	}
	
	/**
	 * @param string $url
	 * @param string $label Link text
	 * @return string Complete HTML link
	 */
	protected function _link($url, $label)
	{
		return "<a href=\"$url\"" .($this->class ? " class=\"{$this->class}\"" : '') . ">$label</a>";
	}

}
