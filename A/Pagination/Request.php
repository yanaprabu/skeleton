<?php

$adapter = new A_Pagination_Adapter_Array (...);
$pager = new A_Pagination_Request ($adapter, 5);
$view = new A_Pagination_View_Standard();


class A_Pagination_Request	{

	protected $adapter;
	protected $pager;

	public function __construct()	{
		if (func_get_arg(0) instanceof A_Pagination)	{
			$this->pager = func_get_arg(0);
			$this->adapter = $this->pager->getDataSource();
		} elseif (func_get_arg(0) instanceof A_Pagination_Adapter_Interface)	{
			$pageSize = func_get_arg(1);
			$currentPage = func_get_arg(2);
			$this->pager = new A_Pagination ($adapter, $pageSize, $currentPage);
			$this->adapter = func_get_arg(0);
		}
		$this->url = new A_Pagination_Url();
		$this->process(); // would we ever need to not call $process?
	}

	public function process()	{
		$this->pager->setcurrentPage (intval ($this->get ('page'), $this->pager->getFirstPage()));
		$this->url->set ('page', $this->get ('page'));
		if ($pageSize = $this->get ('page_size'))	{
			$this->pager->setPageSize (intval ($pageSize));
			$this->set ('page_size', $pageSize);
		}
		if ($orderBy = $this->get ('order_by'))	{
			list ($field, $dir) = explode ('_', $orderBy);
			$this->adapter->setOrderBy ($field, ($dir == 'asc' ? false : true));
			$this->set ('order_by', $field . '_' . $dir);
		}
	}

	public function get ($param, $default = '')	{
		return (isset ($_GET[$this->pager->getParamName ($key)]) ? $_GET[$this->pager->getParamName ($key)] : $default);
	}

	public function set ($param, $value)	{
		$this->url->set ($this->pager->getParamName ($param), $value);
		$this->pager->setParam ($this->pager->getParamName ($param), $value);
	}


}