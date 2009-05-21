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

	protected $helpers = array();

	public function __construct ($pager, $url=false)	{
		$this->pager = $pager;
		$this->helpers['url'] = $url ? $url : new A_Pagination_Url();
	}

	public function setHelper ($name, $helper)	{
		$this->helpers[$name] = $helper;
	}

	public function order()	{
		if (! isset($this->helpers['order']))	{
			include_once 'A/Pagination/View/Order.php';
			$this->helpers['order'] = new A_Pagination_View_Order ($this->pager);
		}
		return $this->helpers['order'];
	}

	public function link()	{
		if (! isset($this->helpers['link']))	{
			include_once 'A/Pagination/View/Link.php';
			$this->helpers['link'] = new A_Pagination_View_Link ($this->pager, $this->url());
		}
		return $this->helpers['link'];
	}

	public function url()	{
		if (! isset($this->helpers['url']))	{
			include_once 'A/Pagination/View/Link.php';
			$this->helpers['url'] = new A_Pagination_Url ($this->pager);
			return $this->helpers['url'];
		}
	}

	public function render()	{
		$links = '';
		$links .= $this->link()->first('First');
		$links .= $this->link()->previous('Previous');
		$links .= $this->link()->range();
		$links .= $this->link()->next('Next');
		$links .= $this->link()->last('Last');
		return $links;
	}

/*
	public function __call ($method, $params)   {
		if (!isset($this->helpers[$method])) {
			$name = ucfirst($method);
			include_once 'A/Pagination/View/'. $name . '.php';
			$class = "A_Pagination_View_$name";
			$this->helpers[$method] = new $class($this->pager);
		}
		return $this->helpers[$method];
	}
*/

}