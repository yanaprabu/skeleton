<?php
/**
 * Request processer for A_Pager 
 * 
 * @package A_Pager
 * @deprecated replaced by A_Pagination package
 * @see A_Pagination
 */

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
