<?php
/**
 * A_Pagination_View_Order
 *
 * Generate orderBy links
 *
 * @author Cory Kaufman, Christopher Thompson
 * @package A_Pagination
 * @version @package_version@
 */

class A_Pagination_Helper_Order	{
	protected $pager;
	protected $url;
	protected $columns;
	protected $separator = '</th><th>';
	
	public function __construct ($pager, $url=false, $columns=array()) {
		$this->pager = $pager;
		$this->url = $url ? $url : new A_Pagination_Helper_Url();
		$this->columns = $columns;
	}

	public function setColumns($columns) {
		$this->columns = $columns;
	}

	public function setSeparator($separator) {
		$this->separator = $separator;
	}

	public function render($columns=array()) {
		if ($columns) {
			$this->columns = $columns;
		}
		$link = new A_Pagination_Helper_Link($this->pager, $this->url);
		$links = array();
		foreach ($this->columns as $column => $label) {
			$links[] = $column ? $link->order($column, $label) : $label;
		}
		return implode($this->separator, $links);
	}

}