<?php

class A_Pagination_Standard	{

	public function __construct ($adapter, $pageSize, $currentPage)	{
		$this->pager = new A_Pagination_Request ($adapter, $pageSize, $currentPage);
		$this->view = new A_Pager_View_Standard ($this->pager);
	}

	public function __call ($method, $params)   {
		if (method_exists ($this->view, $method))   {
			return call_user_func_array (array ($this->view, $method), $params);
		} elseif (method_exists ($this->pager, $method))    {
			return call_user_func_array (array ($this->pager, $method), $params);
		} else  {
			throw new Exception ("Method $method does not exist");
		}
	}

}