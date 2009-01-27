<?php
/**
 * Request processer that saves setting in Session for A_Pager 
 * 
 * @package A_Pager 
 */

class A_Pager_Request_Session {
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
			$_SESSION[$this->session_name]['order_by_current_field'] = $field;
			$_SESSION[$this->session_name]['order_by_current_desc'] = $direction;
		} elseif (isset($_SESSION[$this->session_name]['order_by_current_field'])) {
			$pager->order_by_current_field = $_SESSION[$this->session_name]['order_by_current_field'];
			$pager->order_by_current_desc = $_SESSION[$this->session_name]['order_by_current_desc'];
		} else {
			$_SESSION[$this->session_name]['order_by_current_field'] = $pager->order_by_current_field;
			$_SESSION[$this->session_name]['order_by_current_desc'] = $pager->order_by_current_desc;
		}
		if ($pager->order_by_current_field !== '') {
			$pager->datasource->setOrderBy($pager->order_by_fields[$pager->order_by_current_field], $pager->order_by_current_desc);
		}

		$pager->setCurrentPage($pager->current_page);

	}

}

