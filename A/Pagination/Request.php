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
include_once 'A/Pagination/Url.php';

class A_Pagination_Request extends A_Pagination_Core	{

	public function process()	{
		if ($numItems = $this->get('num_items')) {
			$this->setNumItems(intval($numItems));
		}
		if ($pageSize = $this->get('page_size')) {
			$this->setPageSize(intval($pageSize));
		}
		if ($orderBy = $this->get('order_by'))	{
			list ($field, $dir) = explode('_', $orderBy);
			$this->setOrderBy ($field, ($dir == 'desc' ? true : false));
		}
		$this->setcurrentPage(intval($this->get('page')), $this->getFirstPage());	// do after getting num_items
	}

	public function get($param, $default='')	{
		$name = $this->getParamName($param);
		return isset($_GET[$name]) ? $_GET[$name] : $default;
	}

}