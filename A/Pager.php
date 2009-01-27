<?php
/**
 * Pagination class 
 * 
 * @package A_Pager 
 */

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
		return $this;
	}

	public function setRangeSize($n) {
		if ($n > 2) {
			$this->range_size = $n;
		}
		return $this;
	}

	public function setPageParameter($name) {
		$this->page_param = $name;
		return $this;
	}

	public function setPageSizeParameter($name) {
		$this->page_size_param = $name;
		return $this;
	}

	public function setLastRowParameter($name) {
		$this->last_row_param = $name;
		return $this;
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
			
		return $this;
	}

	public function setOrderByFields($fields, $default_field=false) {
		$this->order_by_fields = $fields;
		if ($default_field !== false) {
			$this->order_by_default_field = array_search($default_field, $fields);
		} else {
			$this->order_by_default_field = 0;		// use first field as default
		}
		$this->setOrderByCurrent($this->order_by_default_field, 0);
		return $this;
	}

	public function setOrderByParameter($name) {
		$this->order_by_param = $name;
		return $this;
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
		return $this;
	}

	public function getPageSize() {
		return $this->page_size;
	}

	public function getCurrentPage() {
		return $this->current_page;
	}

	public function getPrevPage($length=1)	{
		if ($length < 1) {
			$length = 1;
		}
		$page = $this->current_page - $length;
		if ($page > $this->first_page) {
			return $page;
		} else {
			return $this->first_page;
		}		
	}
	
	public function getNextPage($length=1)	{
		if ($length < 1) {
			$length = 1;
		}
		$page = $this->current_page + $length;
		if ($page < $this->last_page) {
			return $page;
		} else {
			return $this->last_page;
		}
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

