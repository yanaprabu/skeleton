<?php

class A_Pagination_View_Standard	{

	protected $helpers = array();

	public function __construct ($pager)	{
		$this->pager = $pager;
		$this->helpers['url'] = new A_Pagination_Url();
	}

	public function setHelper ($name, $helper)	{
		$this->helpers[$name] = $helper;
	}

	public function order()	{
		if ($this->helpers['order'] !== null)	{
			return $this->helpers['order'];
		} else	{
			$this->helpers['order'] = new A_Pagination_View_Order ($this);
			return $this->helpers['order'];
		}
	}

	public function link()	{
		if ($this->helpers['link'] !== null)	{
			return $this->helpers['link'];
		} else	{
			$this->helpers['link'] = new A_Pagination_View_Link ($this);
			return $this->helpers['link'];
		}
	}

	public function url()	{
		if ($this->helpers['url'] !== null)	{
			return $this->helpers['url'];
		} else	{
			$this->helpers['url'] = new A_Pagination_View_Link ($this);
			return $this->helpers['url'];
		}
	}

	public function render()	{

	}

}