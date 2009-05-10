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
		$this->pager->setcurrentPage (intval ($this->get ('page'), $this->pager->getFirstPage()));
		$this->url->set ('page', $this->get ('page'));
		if ($size = $this->get ('page_size'))	{
			$this->pager->setPageSize (intval ($size));
			$this->url->set ('page_size', $size);
		}
		if ($orderBy = $this->get ('order_by'))	{
			list ($field, $dir) = explode ('_', $orderBy);
			$this->adapter->setOrderBy ($field, ($dir == 'asc' ? false : true));
			$this->url->set ('order_by', $field . '_' . $dir);
		}
	}

	public function get ($key, $default = '')	{
		return (isset ($_GET[$key]) ? $_GET[$key] : $default);
	}


}