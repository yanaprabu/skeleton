<?php
/**
 * Pagination class
 *
 * @package  A
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Pager
 */
class A_Pager {
	/**
	 * @var mixed $datasource Pageable datasource object
	 */
	public $datasource = null;
	/**
	 * @var integer $page_size Number of rows of data per page
	 */
	public $page_size = 10;
	/**
	 * @var integer $range_size Number of links in pager
	 */
	public $range_size = 10;
	/**
	 * @var integer $current_page Number of current page
	 */
	public $current_page = 0;
	/**
	 * @var integer $first_page ???
	 */
	public $first_page = 1;
	/**
	 * @var integer $last_page ???
	 */
	public $last_page = -1;
	/**
	 * @var integer $first_row ???
	 */
	public $first_row = 1;
	/**
	 * @var integer $list_row ???
	 */
	public $last_row = 0;
	/**
	 * @var integer $start_now ???
	 */
	public $start_row = 0;
	/**
	 * @var integer $end_row ???
	 */
	public $end_row = 0;
	/**
	 * @var string $page_param Parameter names for URLs
	 */
	public $page_param = 'page';
	/**
	 * @var string $last_row_param ???
	 */
	public $last_row_param = 'last_row';
	/**
	 * @var string $page_size_param ???
	 */
	public $page_size_param = 'page_size';
	/**
	 * @var string $order_by_param ???
	 */
	public $order_by_param = 'order_by';
	/**
	 * @var array $order_by_fields field names in database e.g. myfield or mytable.myfield
	 */
	public $order_by_fields = array();
	/**
	 * @var string $order_by_default_field Initial sort order
	 */
	public $order_by_default_field = '';
	/**
	 * @var string $order_by_current_field Currently sorting on this field
	 */
	public $order_by_current_field = '';
	/**
	 * @var integer $order_by_current_desc 0=ascending or 1=descending for current field
	 */
	public $order_by_current_desc = 0;
	
	/**
	 * __construct
	 *
	 * @param mixed $datasource ???
	 */
	public function __construct($datasource) {
		$this->datasource = $datasource;
	}
	
	/**
	 * setPageSize
	 *
	 * @param integer $n Number of results to show on each page
	 * @return A_Pager This object instance
	 */
	public function setPageSize($n) {
		if ($n > 0) {
			$this->page_size = $n;
		}
		return $this;
	}
	
	/**
	 * setRangeSize
	 *
	 * @param integer $n ???
	 * @return A_Pager This object instance
	 */
	public function setRangeSize($n) {
		if ($n > 2) {
			$this->range_size = $n;
		}
		return $this;
	}
	
	/**
	 * setPageParameter
	 *
	 * @param mixed $name ???
	 * @return A_Pager This object instance
	 */
	public function setPageParameter($name) {
		$this->page_param = $name;
		return $this;
	}

	/**
	 * setPageSizeParameter
	 *
	 * @param mixed $name ???
	 * @return A_Pager This object instance
	 */
	public function setPageSizeParameter($name) {
		$this->page_size_param = $name;
		return $this;
	}

	/**
	 * setLastRowParameter
	 *
	 * @param mixed $name ???
	 * @return A_Pager This object instance
	 */
	public function setLastRowParameter($name) {
		$this->last_row_param = $name;
		return $this;
	}

	/**
	 * setOrderByCurrent
	 *
	 * @param mixed $field ???
	 * @param mixed $direction ???
	 * @return A_Pager This object instance
	 */
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

	/**
	 * setOrderByFields
	 *
	 * @param mixed $fields ???
	 * @param mixed $default_field ??? (optional)
	 * @return A_Pager This object instance
	 */
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

	/**
	 * setOrderByParameter
	 *
	 * @param mixed $name ???
	 * @return A_Pager This object instance
	 */
	public function setOrderByParameter($name) {
		$this->order_by_param = $name;
		return $this;
	}

	/**
	 * getOrderByParameter
	 *
	 * @param mixed $field ???
	 * @return array ???
	 */
	public function getOrderByParameter($field) {
		$key = array_search($field, $this->order_by_fields);
		if ($key !== false) {
			$params[$this->order_by_param] = $key . '_' . $this->order_by_direction[$key];
		} else {
			$params = array();
		}
		return $params;
	}

	/**
	 * setCurrentPage
	 *
	 * @param mixed $n Page to show
	 * @return A_Pager This object instance
	 */
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

	/**
	 * getPageSize
	 *
	 * @return mixed The current page size
	 */
	public function getPageSize() {
		return $this->page_size;
	}

	/**
	 * getCurrentPage
	 *
	 * @return mixed The current page
	 */
	public function getCurrentPage() {
		return $this->current_page;
	}

	/**
	 * getPrevPage
	 *
	 * @param integer $length ??? (optional)
	 * @return mixed The index of the previous page
	 */
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
	
	/**
	 * getNextPage
	 *
	 * @param integer $length ??? (optional)
	 * @return mixed The index of the next page
	 */
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

	/**
	 * getLastPage
	 *
	 * @return mixed The index of the last page
	 */
	public function getLastPage() {
		return $this->last_page;
	}

	/**
	 * getFirstPage
	 *
	 * @return mixed The index of the first page
	 */
	public function getFirstPage() {
		return $this->first_page;
	}

	/**
	 * getFirstRow
	 *
	 * @return mixed ???
	 */
	public function getFirstRow() {
		return $this->first_row;
	}
	
	/**
	 * getLastRow
	 *
	 * @return mixed ???
	 */
	public function getLastRow() {
		return $this->last_row;
	}
	
	/**
	 * getStartRow
	 *
	 * @return mixed ???
	 */
	public function getStartRow() {
		return $this->start_row;
	}

	/**
	 * getEndRow
	 *
	 * @return mixed ???
	 */
	public function getEndRow() {
		return $this->end_row;
	}

	/**
	 * getValues
	 *
	 * @return mixed ???
	 */
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

	/**
	 * getRange
	 *
	 * @param integer $range_start Beginning of range to get (optional)
	 * @param integer $range_end End of range to get (optional)
	 * @return array Range of results from $range_start to $range_end
	 */
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

	/**
	 * getRows
	 *
	 * @param integer $start_row ??? (optional)
	 * @param integer $end_row ??? (optional)
	 * @return mixed ???
	 */
	public function getRows($start_row=0, $end_row=0) {
		if (($start_row == 0) && ($end_row == 0)) {
			$start_row = $this->start_row;
			$end_row = $this->end_row;
		}
		return $this->datasource->getRows($start_row, $end_row);
	}

	/**
	 * hasPages
	 *
	 * @return boolean True if there are more than one pages
	 */
	public function hasPages() {
		return $this->last_page > 1;
	}

}

