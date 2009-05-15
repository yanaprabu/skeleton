<?php

class A_Pagination_Request	{

	protected $adapter;
	protected $pager;

	public function __construct ($adapter, $pageSize = 10, $currentPage = 1)	{
		$this->adapter = $adapter;
		$this->pager = new A_Pagination ($adapter, $pageSize, $currentPage);
		$this->url = new A_Pagination_Url();
		$this->process(); // would we ever need to not call $process?
	}

	public function process()	{
		$this->setcurrentPage (intval ($this->get ('page'), $this->getFirstPage()));
		$this->url->set ('page', $this->get ('page'));
		if ($pageSize = $this->get ('page_size'))	{
			$this->setPageSize (intval ($pageSize));
			$this->set ('page_size', $pageSize);
		}
		if ($orderBy = $this->get ('order_by'))	{
			list ($field, $dir) = explode ('_', $orderBy);
			$this->adapter->setOrderBy ($field, ($dir == 'asc' ? false : true));
			$this->set ('order_by', $field . '_' . $dir);
		}
	}

	public function get ($param, $default = '')	{
		return (isset ($_GET[$this->getParamName ($key)]) ? $_GET[$this->getParamName ($key)] : $default);
	}

	public function set ($param, $value)	{
		$this->url->set ($this->getParamName ($param), $value);
		$this->pager->setParam ($this->getParamName ($param), $value);
	}


}