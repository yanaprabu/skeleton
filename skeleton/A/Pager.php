<?php

class A_Pager {
	public $datasource = null;					// Pageable datasource object
	public $page_size = 10;					// number of rows of data per page
	public $range_size = 10;					// number of links in pager
	public $current_page = 0;
	public $first_page = 1;
	public $last_page = -1;
	public $first_row = 1;
	public $last_row = 0;
	public $start_row = 0;
	public $end_row = 0;
	
	public $page_param = 'page';				// parameter names for URLs
	public $last_row_param = 'last_row';
	public $page_size_param = 'page_size';
	public $order_by_param = 'order_by';
	public $order_by_fields = array();			// field names in database e.g. myfield or mytable.myfield
	public $order_by_default_field = '';		// initial sort order
	public $order_by_current_field = '';	// currently sorting on this field
	public $order_by_current_desc = 0;			// 0=ascending or 1=descending for current field
		
	public function __construct($datasource) {
		$this->datasource = $datasource;
	}

	public function setPageSize($n) {
		if ($n > 0) {
			$this->page_size = $n;
		}
	}

	public function setRangeSize($n) {
		if ($n > 2) {
			$this->range_size = $n;
		}
	}

	public function setPageParameter($name) {
		$this->page_param = $name;
	}

	public function setPageSizeParameter($name) {
		$this->page_size_param = $name;
	}

	public function setLastRowParameter($name) {
		$this->last_row_param = $name;
	}

	public function setOrderByCurrent($field, $direction) {
		$this->order_by_current_field = $field;
		$pager->order_by_current_desc = $direction;
		$current = 0;
		$other = 0;
/*

		if ($direction) {
			$current = 0;
			$other = 1;
		} else {
			$current = 1;
			$other = 0;
		}
*/
		foreach ($this->order_by_fields as $key => $name) {
			if ($key == $this->order_by_current_field) {
				$this->order_by_direction[$key] = $current;
			} else {
				$this->order_by_direction[$key] = $other;
			}
		}
			
	}

	public function setOrderByFields($fields, $default_field=false) {
		$this->order_by_fields = $fields;
		if ($default_field !== false) {
			$this->order_by_default_field = array_search($default_field, $fields);
		} else {
			$this->order_by_default_field = 0;		// use first field as default
		}
		$this->setOrderByCurrent($this->order_by_default_field, 0);
	}

	public function setOrderByParameter($name) {
		$this->order_by_param = $name;
	}

	public function getOrderByParameter($field) {
		$key = array_search($field, $this->order_by_fields);
		if ($key !== false) {
			$params[$this->order_by_param] = $key . '_' . $this->order_by_direction[$key];
		} else {
			$params = array();
		}
		return $params;
	}

	public function setCurrentPage($n) {
		if ($this->last_row < 1) {			// do not access datasource if last_row has been set
			$this->last_row = $this->datasource->getNumRows();
		}
		if ($this->last_row > 0) {
			$this->last_page = ceil($this->last_row / $this->page_size);
			if ($n < $this->first_page) {
				$n = $this->first_page;
			}
			if ($n > $this->last_page) {
				$n = $this->last_page;
			}
			$this->current_page = $n;
			$this->start_row = ($n - 1) * $this->page_size + 1;
			$this->end_row = $this->start_row + $this->page_size - 1;
			if ($this->end_row > $this->last_row) {
				$this->end_row = $this->last_row;
			}
		} else {
			$this->last_page = 0;
			$this->current_page = 0;
			$this->start_row = 0;
			$this->end_row = 0;
		}
	}

	public function getPageSize() {
		return $this->page_size;
	}

	public function getCurrentPage() {
		return $this->current_page;
	}

	public function getLastPage() {
		return $this->last_page;
	}

	public function getFirstPage() {
		return $this->first_page;
	}

	public function getFirstRow() {
		return $this->first_row;
	}
	
	public function getLastRow() {
		return $this->last_row;
	}
	
	public function getStartRow() {
		return $this->start_row;
	}

	public function getEndRow() {
		return $this->end_row;
	}

	public function getValues() {
		return array(
			'page_size' => $this->page_size,
			'page_size' => $this->last_page,
			'current_page' => $this->current_page,
			'start_row' => $this->start_row,
			'end_row' => $this->end_row,
			'last_row' => $this->last_row,
			);
	}

	public function getRange($range_start=0, $range_end=0) {
		if ($range_start == 0) {
			// get number of links each side of current page
			$side_size = floor($this->range_size / 2);
			$range_start = $this->current_page - $side_size;
			$range_end = $range_start + $this->range_size - 1;
		}
		// bounds check start and end
		if ($range_start < $this->first_page) {
			$range_start = $this->first_page;
		}
		if ($range_start > $this->last_page) {
			$range_start = $this->last_page;
		}
		if ($range_end > $this->last_page) {
			$range_end = $this->last_page;
		} elseif ($range_end < $range_start) {
			$range_end = $range_start;
		}
		return range($range_start, $range_end);
	}

	public function getRows($start_row=0, $end_row=0) {
		if (($start_row == 0) && ($end_row == 0)) {
			$start_row = $this->start_row;
			$end_row = $this->end_row;
		}
		return $this->datasource->getRows($start_row, $end_row);
	}

	public function hasPages() {
		return $this->last_page > 1;
	}

}


class A_Pager_Request {
	protected $pager;

	public function __construct($pager) {
		$this->pager = $pager;
	}

	public function process() {
		if (isset($this->pager)) {
			if (isset($_GET[$this->pager->page_param])) {
				$this->pager->current_page = intval($_GET[$this->pager->page_param]);
			} else {
				$this->pager->current_page = $this->pager->first_page;
			}
			
			if (isset($_GET[$this->pager->last_row_param])) {
				$this->pager->last_row = intval($_GET[$this->pager->last_row_param]);
			} else {
				$this->pager->last_row = $this->pager->datasource->getNumRows();
			}
			
			if (isset($_GET[$this->pager->page_size_param])) {
				$this->pager->page_size = intval($_GET[$this->pager->page_size_param]);
			}
		
			if (isset($_GET[$this->pager->order_by_param])) {
				list($field, $direction) = explode('_', $_GET[$this->pager->order_by_param]);
				$field = intval($field);
				if (isset($this->pager->order_by_fields[$field])) {		// must be a registered field
					$this->pager->setOrderByCurrent($field, intval($direction));
				}
			}
			if ($this->pager->order_by_current_field !== '') {
				$this->pager->datasource->setOrderBy($this->pager->order_by_fields[$this->pager->order_by_current_field], $this->pager->order_by_current_desc);
			}
		
		
			$this->pager->setCurrentPage($this->pager->current_page);
		}
	}

}


class A_Pager_SessionRequest {
	protected $pager;
	protected $session_name = 'Pager';
	protected $page_resume = 'resume';
	protected $last_row_recalc = 'recalc';

	public function __construct($pager) {
		$this->pager = $pager;
	}

	public function process() {
		if (!isset($_SESSION)) {
			session_start();
		}
		$pager = $this->pager;
		$resume = false;
		if (! isset($_GET[$pager->page_param])) {
			$pager->current_page = $pager->first_page;
			unset($_SESSION[$this->session_name]);		// clear any previous values
			$_SESSION[$this->session_name]['page'] = $pager->current_page;
		} else {
			if (($_GET[$pager->page_param] === $this->page_resume) && (isset($_SESSION[$this->session_name]['page']))) {
				$pager->current_page = $_SESSION[$this->session_name]['page'];
				$resume = true;
			} else {
				$pager->current_page = intval($_GET[$pager->page_param]);
				$_SESSION[$this->session_name]['page'] = $pager->current_page;
			}
		}
		
		if ((! isset($_GET[$pager->last_row_param]) && (! $resume)) 
					|| ((isset($_GET[$pager->last_row_param]) && ($_GET[$pager->last_row_param] === $this->last_row_recalc)))) {
			$pager->last_row = $pager->datasource->getNumRows();
			$_SESSION[$this->session_name]['last_row'] = $pager->last_row;
		} elseif (isset($_SESSION[$this->session_name]['last_row'])) {
			$pager->last_row = $_SESSION[$this->session_name]['last_row'];
		} else {
			$_SESSION[$this->session_name]['last_row'] = $pager->last_row;
		}
 
		if (isset($_GET[$pager->page_size_param])) {
			$pager->page_size = intval($_GET[$pager->page_size_param]);
			$_SESSION[$this->session_name]['page_size'] = $pager->page_size;
		} elseif (isset($_SESSION[$this->session_name]['page_size'])) {
			$pager->page_size = $_SESSION[$this->session_name]['page_size'];
		} else {
			$_SESSION[$this->session_name]['page_size'] = $pager->page_size;
		}

		if (isset($_GET[$pager->order_by_param])) {
			list($field, $direction) = explode('_', $_GET[$pager->order_by_param]);
			$field = intval($field);

			if (isset($pager->order_by_fields[$field])) {		// must be a registered field
				$pager->setOrderByCurrent($field, intval($direction));
			}
		}
		if ($pager->order_by_current_field !== '') {
			$pager->datasource->setOrderBy($pager->order_by_fields[$pager->order_by_current_field], $pager->order_by_current_desc);
		}

		$pager->setCurrentPage($pager->current_page);

	}

}


class A_Pager_HTMLWriter {
	protected $pager = null;
	protected $no_current_link = true;			// no link for current page
	protected $base_url = '';					// domain and script name part of URL
	protected $extra_params = array();			// array of parameters that are added to all URLs

	public function __construct($pager) {
		$this->pager = $pager;
	}

	public function getParameters($n=0) {
		$params = $this->extra_params;
		if ($n > 0) {
			$params[$this->pager->page_param] = $n;
		}
		$params[$this->pager->page_size_param] = $this->pager->page_size;
		$params[$this->pager->last_row_param] = $this->pager->last_row;
		if ($this->pager->order_by_current_field != '') {
			$orderby = $this->pager->getOrderByParameter($this->pager->order_by_fields[$this->pager->order_by_current_field]);
			if ($orderby) {
				$params = array_merge($params, $orderby);
			}
		}
		return $params;
	}

	public function setExtraParameters($params=array()) {
		$this->extra_params = $params;
	}

	public function setBaseUrl($url) {
		$this->base_url = $url;
	}

	public function getPageUrl($n, $params=array()) {
		if (($n > 0) && ($n <= $this->pager->last_page)) {
			$params = array_merge($this->getParameters($n), $params);
			foreach ($params as $name => $value) {
				$param_strs[$name] = $name . '=' . $value;
			}
			$url = $this->base_url . '?' . implode('&', $param_strs);
		} else {
			$url = '';
		}
		return $url;
	}

	public function getCurrentUrl() {
		return $this->getPageURL($this->pager->current_page);
	}

	public function getPrevURL() {
		if ($this->pager->current_page > $this->pager->first_page) {
			$url = $this->getPageURL($this->pager->current_page - 1);
		} else {
			$url = '';
		}
		return $url;
	}

	public function getNextURL() {
		if ($this->pager->current_page < $this->pager->last_page) {
			$url = $this->getPageURL($this->pager->current_page + 1);
		} else {
			$url = '';
		}
		return $url;
	}

	public function getFirstURL() {
		if ($this->pager->current_page > $this->pager->first_page) {
			$url = $this->getPageURL($this->pager->first_page);
		} else {
			$url = '';
		}
		return $url;
	}

	public function getLastURL() {
		if ($this->pager->current_page < $this->pager->last_page) {
			$url = $this->getPageURL($this->pager->last_page);
		} else {
			$url = '';
		}
		return $url;
	}

	public function getRangeURLs($start=0, $end=0) {
		foreach ($this->pager->getRange($start, $end) as $n) {
			$urls[$n] = $this->getPageURL($n);
		}
		return $urls;
	}

	public function getPageSizeURL($size) {
		$params[$this->pager->page_size_param] = $size;
		return $this->getPageURL($this->pager->current_page, $params);
	}

	public function getOrderByURL($field) {
		$params = $this->pager->getOrderByParameter($field);
		return $this->getPageURL($this->pager->current_page, $params);
	}

	public function getLink($n, $text='', $attrs='', $params=array()) {
		if (($n > 0) && ($n <= $this->pager->last_page)) {
			$link = '<a href="' . $this->getPageURL($n, $params) . "\" $attrs>" . ($text ? $text : $n) . '</a>';
		} else {
			$link = '';
		}
		return $link;
	}

	public function getPageLink($n, $text='', $attrs='', $params=array()) {
		if ($this->no_current_link && ($n == $this->pager->current_page)) {
			$link = ($text ? $text : $n);
		} else {
			$link = $this->getLink($n, $text, $attrs);
		}
		return $link;
	}

	public function getPrevLink($text='Prev', $attrs='') {
		if ($this->pager->current_page > $this->pager->first_page) {
			$url = $this->getPageLink($this->pager->current_page - 1, $text, $attrs);
		} else {
			$url = '';
		}
		return $url;
	}

	public function getNextLink($text='Next', $attrs='') {
		if ($this->pager->current_page < $this->pager->last_page) {
			$url = $this->getPageLink($this->pager->current_page + 1, $text, $attrs);
		} else {
			$url = '';
		}
		return $url;
	}

	public function getFirstLink($text='First', $attrs='') {
		if ($this->pager->current_page > $this->pager->first_page) {
			$url = $this->getPageLink($this->pager->first_page, $text, $attrs);
		} else {
			$url = '';
		}
		return $url;
	}

	public function getLastLink($text='Last', $attrs='') {
		if ($this->pager->current_page < $this->pager->last_page) {
			$url = $this->getPageLink($this->pager->last_page, $text, $attrs);
		} else {
			$url = '';
		}
		return $url;
	}

	public function getRangeLinks($start=0, $end=0, $attrs='') {
		foreach ($this->pager->getRange($start, $end) as $n) {
			$links[$n] = $this->getPageLink($n, '', $attrs);
		}
		return $links;
	}

	public function getPageSizeLink($size, $text='', $attrs='') {
		if ($size > 0) {
			$link = '<a href="' . $this->getPageSizeURL($size) . "\" $attrs>" . ($text ? $text : $size) . '</a>';
		} else {
			$link = '';
		}
		return $link;
	}

	public function getOrderByLink($field, $text, $attrs='') {
		$params = $this->pager->getOrderByParameter($field);
		return $this->getLink($this->pager->current_page, $text, $attrs, $params);
	}

}

