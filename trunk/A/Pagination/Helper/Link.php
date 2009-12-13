<?php
/**
 * A_Pagination_View_Link
 *
 * Genreate HTML links
 *
 * @author Cory Kaufman, Christopher Thompson
 * @package A_Pagination
 * @version @package_version@
 */

#include_once 'A/Pagination/Helper/Url.php';

class A_Pagination_Helper_Link {
	protected $pager;
	public $url;
	protected $class = false;
	protected $separator = ' ';
	protected $alwaysShowFirstLast = false;
	protected $alwaysShowPreviousNext = false;

	/**
	 * @param $pager is a core pagination object
	 * @param $url is a URL object. One will be created if not passed. 
	 */
	public function __construct($pager, $url=false)	{
		$this->pager = $pager;
		$this->url = $url ? $url : new A_Pagination_Helper_Url();
		$page = $this->pager->getCurrentPage();
		if ($page > 1) {
			$this->url->set ($this->pager->getParamName('page'), $page);
		}
		$num_items = $this->pager->getNumItems();
		if ($num_items) {
			$this->url->set ($this->pager->getParamName('num_items'), $num_items);
		}
		$order_by = $this->pager->getOrderBy();
		if ($order_by) {
			$this->url->set ($this->pager->getParamName('order_by'), $order_by);
		}
	}

	/**
	 * @param $separator string containing the text to put between links
	 * @return
	 */
	public function setSeparator ($separator) {
		$this->separator = $separator;
		return $this;
	}

	/**
	 * @param $class is the class name
	 * @return
	 */
	public function setClass($class) {
		$this->class = $class;
		return $this;
	}

	/**
	 * @param $always boolean to set whether first/last links are shown
	 * @return
	 */
	public function alwaysShowFirstLast($always=true) {
		$this->alwaysShowFirstLast = $always;
		return $this;
	}

	/**
	 * @param $always boolean to set whether next/prev links are shown
	 * @return
	 */
	public function alwaysShowPreviousNext($always=true) {
		$this->alwaysShowPreviousNext = $always;
		return $this;
	}

	/**
	 * @param $label string containing text for link
 	 * @param $separator string containing the text to put after link
	 * @return string containing HTML link or '' if not in range
	 */
	public function first($label=false, $separator=true)	{
		$page = $this->pager->getFirstPage();
		if (!$this->pager->inPageRange($page) || $this->alwaysShowFirstLast == true) {
			return $this->page($page, $label) . ($separator ? $this->separator : '');
		}
		return '';
	}

	/**
	 * @param $label string containing text for link
	 * @param $separator string containing the text to put after link
	 * @return string containing HTML link or '' if not in range
	 */
	public function previous ($label=false, $separator=true)	{
		if ($this->pager->isPage(-1) || $this->alwaysShowPreviousNext == true) {
			return $this->page($this->pager->getPage(-1), $label) . ($separator ? $this->separator : '');
		}
		return '';
	}

	/**
	 * @param $page integer page number
	 * @param $label string containing text label to use instead of page number
	 * @return string containing HTML link or '' if not in range
	 */
	public function page($page=false, $label=false) {
		return $this->_link($this->url->render(false, array ($this->pager->getParamName('page') => $page)), $label ? $label : $page);
	}

	/**
	 * @param $label string containing text for link
	 * @param $separator string containing the text to put before link
	 * @return string containing HTML link or '' if not in range
	 */
	public function next($label=false, $separator=true) {
		if ($this->pager->isPage(+1) || $this->alwaysShowPreviousNext == true) {
			return ($separator ? $this->separator : '') . $this->page($this->pager->getPage(+1), $label);
		}
		return '';
	}

	/**
	 * @param $label string containing text for link
	 * @param $separator string containing the text to put before link
	 * @return string containing HTML link or '' if not in range
	 */
	public function last($label=false, $separator=true) {
		$page = $this->pager->getLastPage();
		if (!$this->pager->inPageRange($page) || $this->alwaysShowFirstLast == true) {
			return ($separator ? $this->separator : '') . $this->page($page, $label);
		}
		return '';
	}

	/**
	 * @param $offset
	 * @param $page
	 * @return array of HTML link strings
	 */
	public function range($offset=false, $page=false) {
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
	 * @param $field
	 * @param $label
	 * @param $descending
	 * @return
	 */
	public function order($field, $label='', $descending = null) {
		// if descending not specified the currently sorting on this field then reverse sort
		if (($descending === null) && ($field == $this->pager->getOrderBy())) {
			$descending = $this->pager->getOrderByDirection() == 'desc' ? false : true;
		}
		$orderBy = $field . ($descending ? '|desc':'');
		return $this->_link($this->url->render(false, array ($this->pager->getParamName('order_by') => $orderBy), array ($this->pager->getParamName ('page'))), $label ? $label : $page);
	}

	/**
	 * @return string the value of the separator property
	 */
	public function separator () {
		return $this->separator;
	}

	/**
	 * @param url - string containing URL
	 * @param label - string containing link text
	 * @return string - complete HTML link
	 */
	protected function _link($url, $label) {
		return "<a href=\"$url\"" .($this->class ? " class=\"{$this->class}\"" : '') . ">$label</a>";
	}
}