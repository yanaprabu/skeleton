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

include_once 'A/Pagination/Helper/Url.php';

class A_Pagination_Helper_Link {
	protected $pager;
	public $url;
	protected $class = false;
	protected $separator = ' ';
	protected $alwaysShowFirstLast = false;
	protected $alwaysShowPreviousNext = false;

	/**
	 * @param
	 * @type
	 */
	public function __construct($pager, $url=false)	{
		$this->pager = $pager;
		$this->url = $url ? $url : new A_Pagination_Helper_Url();
	}

	/**
	 * @param
	 * @type
	 */
	public function setSeparator ($separator) {
		$this->separator = $separator;
		return $this;
	}

	/**
	 * @param
	 * @type
	 */
	public function setClass($class) {
		$this->class = $class;
		return $this;
	}

	/**
	 * @param
	 * @type
	 */
	public function alwaysShowFirstLast($always=true) {
		$this->alwaysShowFirstLast = $always;
		return $this;
	}

	/**
	 * @param
	 * @type
	 */
	public function alwaysShowPreviousNext($always=true) {
		$this->alwaysShowPreviousNext = $always;
		return $this;
	}

	/**
	 * @param
	 * @type
	 */
	public function first($label=false, $separator=true)	{
		$page = $this->pager->getFirstPage();
		if (!$this->pager->inPageRange($page) || $this->alwaysShowFirstLast == true) {
			return $this->page($page, $label) . ($separator ? $this->separator : '');
		}
		return '';
	}

	/**
	 * @param
	 * @type
	 */
	public function previous ($label=false, $separator=true)	{
		if ($this->pager->isPage(-1) || $this->alwaysShowPreviousNext == true) {
			return $this->page($this->pager->getPage(-1), $label) . ($separator ? $this->separator : '');
		}
		return '';
	}

	/**
	 * @param
	 * @type
	 */
	public function page($page=false, $label=false) {
		return $this->_link($this->url->render(false, $this->_params($this->pager->getParamName('page'), $page)), $label ? $label : $page);
	}

	/**
	 * @param
	 * @type
	 */
	public function next($label=false, $separator=true) {
		if ($this->pager->isPage(+1) || $this->alwaysShowPreviousNext == true) {
			return ($separator ? $this->separator : '') . $this->page($this->pager->getPage(+1), $label);
		}
		return '';
	}

	/**
	 * @param
	 * @type
	 */
	public function last($label=false, $separator=true) {
		$page = $this->pager->getLastPage();
		if (!$this->pager->inPageRange($page) || $this->alwaysShowFirstLast == true) {
			return ($separator ? $this->separator : '') . $this->page($page, $label);
		}
		return '';
	}

	/**
	 * @param
	 * @type
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
	 * @param
	 * @type
	 */
	public function order($field, $label='', $descending = false) {
		$orderBy = $field . ($descending ? '|desc':'');
		return $this->_link($this->url->render(false, $this->_params($this->pager->getParamName('order_by'), $orderBy)), $label ? $label : $page);
	}

	/**
	 * @param
	 * @type
	 */
	public function separator () {
		return $this->separator;
	}

	/**
	 * @param url - string containing URL
	 * @param label - string containing link text
	 * @type string - complete HTML link
	 */
	protected function _link($url, $label) {
		return "<a href=\"$url\"" .($this->class ? " class=\"{$this->class}\"" : '') . ">$label</a>";
	}

	/**
	 * @type array - persisted parameters
	 */
	protected function _params($field, $value) {
		$params = array();
		$page = $this->pager->getCurrentPage();
		if ($page) {
			$params[$this->pager->getParamName('page')] = $page;
		}
		$num_items = $this->pager->getNumItems();
		if ($num_items) {
			$params[$this->pager->getParamName('num_items')] = $num_items;
		}
		$order_by = $this->pager->getOrderBy();
		if ($order_by) {
			$params[$this->pager->getParamName('order_by')] = $order_by;
		}
		$params[$field] = $value;
		return $params;
	}

}