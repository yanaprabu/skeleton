<?php
/**
 * A_Pagination_View_Standard
 *
 * Component to paginate items from a datasource
 *
 * @author Cory Kaufman, Christopher Thompson
 * @package A_Pagination
 * @version @package_version@
 */

class A_Pagination_View_Standard	{

	protected $links = '';
	protected $helpers = array();
	protected $cacheNumItems = true;

	public function __construct ($pager, $url=false, $cache=true)	{
		$this->pager = $pager;
		if ($url)	{
			$this->setHelper ('url', $url);
		}
		$page = $this->pager->getCurrentPage();
		if ($page > 1) {
			$this->url()->set ($this->pager->getParamName('page'), $page);
		}
		$num_items = $this->pager->getNumItems();
		if ($num_items) {
			$this->url()->set ($this->pager->getParamName('num_items'), $num_items);
		}
		$order_by = $this->pager->getOrderBy();
		if ($order_by) {
			$this->url()->set ($this->pager->getParamName('order_by'), $order_by);
		}
	}

	public function setHelper ($name, $helper)	{
		$this->helpers[$name] = $helper;
	}

	public function order()	{
		if (! isset($this->helpers['order']))	{
			#include_once 'A/Pagination/Helper/Order.php';
			$this->helpers['order'] = new A_Pagination_Helper_Order ($this->pager);
		}
		return $this->helpers['order'];
	}

	public function link()	{
		if (! isset($this->helpers['link']))	{
			#include_once 'A/Pagination/Helper/Link.php';
			$this->helpers['link'] = new A_Pagination_Helper_Link ($this->pager, $this->url());
		}
		return $this->helpers['link'];
	}

	public function url()	{
		if (! isset($this->helpers['url']))	{
			#include_once 'A/Pagination/Helper/Url.php';
			$this->helpers['url'] = new A_Pagination_Helper_Url();
		}
		return $this->helpers['url'];
	}

	public function first ($label = false, $separator = true)	{
		$this->links .= $this->link()->first ($label, $separator);
		return $this;
	}

	public function previous ($label = false, $separator = true)	{
		$this->links .= $this->link()->previous ($label, $separator);
		return $this;
	}

	public function page($page=false, $label=false) {
		$this->links .= $this->link->page ($page, $label);
		return $this;
	}

	public function next($label = false, $separator = true)	{
		$this->links .= $this->link()->next($label, $separator);
		return $this;
	}

	public function last($label = false, $separator = true)	{
		$this->links .= $this->link()->last($label, $separator);
		return $this;
	}

	public function range($offset=false, $page=false) {
		$this->links .= $this->link()->range($offset, $page);
		return $this;
	}

	public function render()	{
		if ($this->links == '')	{
			$this->links .= $this->link()->first('First');
			$this->links .= $this->link()->previous('Previous');
			$this->links .= $this->link()->range();
			$this->links .= $this->link()->next('Next');
			$this->links .= $this->link()->last('Last');
		}
		return $this->links;
	}

	public function alwaysShowFirstLast()	{
		$this->link()->alwaysShowFirstLast();
	}

	public function alwaysShowPreviousNext()	{
		$this->link()->alwaysShowPreviousNext();
	}

	public function __call ($method, $params)	{
		if (method_exists ($this->pager, $method))	{
			return call_user_func_array (array ($this->pager, $method), $params);
		}
	}

/*
	public function __call ($method, $params)   {
		if (!isset($this->helpers[$method])) {
			$name = ucfirst($method);
			#include_once 'A/Pagination/View/'. $name . '.php';
			$class = "A_Pagination_View_$name";
			$this->helpers[$method] = new $class($this->pager);
		}
		return $this->helpers[$method];
	}
*/

}