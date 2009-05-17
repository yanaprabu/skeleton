<?php
include_once 'A/Pagination.php';
include_once 'A/Pagination/Url.php';

class A_Pagination_Request extends A_Pagination	{

	public function process()	{
		$this->setcurrentPage($this->get('page'), $this->getFirstPage());
		if ($pageSize = $this->get('page_size')) $this->setPageSize(intval ($pageSize));
		if ($orderBy = $this->get('order_by'))	{
			list ($field, $dir) = explode('_', $orderBy);
			$this->setOrderBy ($field, ($dir == 'desc' ? true : false));
		}
	}

	// Should we rename this method to something more request-specific?
	public function get($param, $default='')	{
		$name = $this->getParamName($param);
		return isset($_GET[$name]) ? $_GET[$name] : $default;
	}

}