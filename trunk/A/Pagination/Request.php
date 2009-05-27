<?php
/**
 * A_Pagination_Request
 *
 * Component to initialize the core object from the request
 *
 * @author Cory Kaufman, Christopher Thompson
 * @package A_Pagination
 * @version @package_version@
 */

include_once 'A/Pagination/Core.php';
include_once 'A/Pagination/Helper/Url.php';

class A_Pagination_Request extends A_Pagination_Core	{

	public function setRequest ($request)	{
		$this->request = $request;
	}

	public function setSession ($session)	{
		$this->session = $session;
	}

	public function process()	{
		if ($numItems = $this->get('num_items')) {
			$this->setNumItems(intval($numItems));
		}
		if ($pageSize = $this->get('page_size')) {
			$this->setPageSize(intval($pageSize));
		}
		if ($orderBy = $this->get('order_by'))	{
			list ($field, $dir) = explode('|', $orderBy);
			$this->setOrderBy ($field, ($dir === 'desc' ? true : false));
		}
		$this->setcurrentPage(intval($this->get('page')), $this->getFirstPage());	// do after getting num_items
	}

	public function get($param, $default='')	{
		$name = $this->getParamName($param);
		// Is get() the standard interface for a request object?
		if ($this->request != null)	{
			if ($this->request->get($name)) return $this->request->get($name);
		}
		if ($this->request != null)	{
			if ($this->session->get($name)) return $this->session->get($name);
		}
		return isset($_GET[$name]) ? $_GET[$name] : $default;
	}

}