<?php
/**
 * Generate HTML links for A_Pager 
 * 
 * @package A_Pager
 * @deprecated replaced by A_Pagination package
 * @see A_Pagination
 */

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
		return $this;
	}

	public function setBaseUrl($url) {
		$this->base_url = $url;
		return $this;
	}

	public function getPageUrl($n, $params=array()) {
		if (($n > 0) && ($n <= $this->pager->last_page)) {
/*
			$params = array_merge($this->getParameters($n), $params);
			foreach ($params as $name => $value) {
				$param_strs[$name] = $name . '=' . $value;
			}
			$url = $this->base_url . '?' . implode('&', $param_strs);
*/
			$url = $this->base_url . '?' . http_build_query(array_merge($this->getParameters($n), $params));
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
