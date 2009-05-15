<?php

class A_Pagination_Request	{

	protected $adapter;
	protected $pager;

	public function __construct ($adapter, $pageSize, $currentPage)	{
		$this->pager = new A_Pagination($adapter, $pageSize, $currentPage);
		$this->process();
	}

	public function process()	{
		$this->pager->setcurrentPage(intval($this->get('page'), $this->pager->getFirstPage()));
		if ($pageSize = $this->get('page_size')) $this->pager->setPageSize(intval ($pageSize));
		if ($orderBy = $this->get('order_by'))	{
			list($field, $dir) = explode('_', $orderBy);
			$this->pager->setOrderBy ($field, ($dir == 'asc' ? false : true));
		}
	}

	public function get ($param, $default = '')	{
		return (isset ($_GET[$this->pager->getParamName($key)]) ? $_GET[$this->pager->getParamName($key)] : $default);
	}

}