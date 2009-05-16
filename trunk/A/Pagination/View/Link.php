<?php

class A_Pagination_View_Link	{

	public function __construct($pager)	{
		$this->pager = $pager;
	}

	public function first()	{
		$html = '<a href="{url}">{text}</a>';
		$html = str_replace ('{url}', $this->pager->url ('page.php', array ('page' => $pager->getFirstPage())));
		$html = str_replace ('{text}', 'First');
		return $html;
	}

	public function previous ($offset = 1)	{
		$html = '<a href="{url}">{text}</a>';
		$html = str_replace ('{url}', $this->pager->url ('page.php', array ('page' => $pager->getCurrentPage() - $offset)));
		$html = str_replace ('{text}', 'Previous');
		return $html;
	}

	public function next ($offset = 1)	{
		$html = '<a href="{url}">{text}</a>';
		$html = str_replace ('{url}', $this->pager->url ('page.php', array ('page' => $pager->getCurrentPage() + $offset)));
		$html = str_replace ('{text}', 'Next');
		return $html;
	}

	public function last()	{
		$html = '<a href="{url}">{text}</a>';
		$html = str_replace ('{url}', $this->pager->url ('page.php', array ('page' => $pager->getLastPage())));
		$html = str_replace ('{text}', 'Last');
		return $html;
	}

	public function separator ($separator)	{

	}

	public function addClass ($class)	{

	}

	public function render()	{

	}

}