<?php

class A_Pagination_Request	{

	public function __construct ($adapter, $pageSize = 10, $currentPage = 1)	{
		$this->pager = new A_Pagination ($adapter, $pageSize, $currentPage);
		$this->process(); // would we ever need to not call $process?
	}

	public function process()	{
		$this->pager->setcurrentPage (intval ($this->get ('page'), $this->pager->getFirstPage()));
		if ($this->get ('page_size')) $this->pager->setPageSize (intval ($this->get ('page_size')));
		// Set up adapter? url helper? view helper?
	}


	public function get ($key, $default = '')	{
		return (isset ($_GET[$key]) ? $_GET[$key] : $default);
	}


}