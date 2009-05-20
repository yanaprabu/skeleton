<?php
/**
 * A_Pagination_View_Link
 *
 * Genreate HTML links
 *
 * @author Cory Kaufman, Christopher Thompson
 * @package A_Pagination
 * @version @package_version@
 */

include_once 'A/Pagination/Url.php';

class A_Pagination_View_Link {
	protected $pager;
	public $url;
	protected $class = false;
	protected $separator = ' ';

	/**
	 * @param 
	 * @type 
	 */
	public function __construct($pager, $url=false)	{
		$this->pager = $pager;
		$this->url = $url ? $url : new A_Pagination_Url();
	}

	/**
	 * @param 
	 * @type 
	 */
	public function setSeparator ($separator) {
		$this->separator = $separator;
		return $this;
	}

	/**
	 * @param 
	 * @type 
	 */
	public function setClass($class) {
		$this->class = $class;
		return $this;
	}

	/**
	 * @param 
	 * @type 
	 */
	public function first($label=false)	{
		$page = $pager->getFirstPage();
		return (!$pager->inPageRange($page)) ? $this->page($page, $label) : '';
	}

	/**
	 * @param 
	 * @type 
	 */
	public function previous ($label=false)	{
		return $this->page(-1, $label);
	}

	/**
	 * @param 
	 * @type 
	 */
	public function page($page=false, $label=false) {
		$html = '';
		if ($this->pager->isPage($page)) {
			$page = $this->pager->getPage($page);
			$param = $this->pager->getParamName('page');
			$html .= '<a href="';
			$html .= $this->url->render(false, array ($param => $page));
			$html .= '"';
			$html .= $this->class ? " class=\"{$this->class}\"" : '';
			$html .= '>';
			$html .= $label ? $label : $page;
			$html .= '</a>';
		}
		return $html;
	}

	/**
	 * @param 
	 * @type 
	 */
	public function next($label=false) {
		return $this->page(1, $label);
	}

	/**
	 * @param 
	 * @type 
	 */
	public function last($label=false) {
		$page = $pager->getLastPage();
		return (!$pager->inPageRange($page)) ? $this->page($page, $label) : '';
	}

	/**
	 * @param 
	 * @type 
	 */
	public function range($offset=false, $page=false) {
		$links = array();
		$current_page = $this->pager->getCurrentPage();
		foreach ($this->pager->getPageRange($offset, $page) as $n) {
			if ($n != $current_page) {
				$links[] = $this->page($n);
			} else {
				$links[] = $n;
			}
		}
		return implode($this->separator, $links);
	}

	/**
	 * @param 
	 * @type 
	 */
	public function separator () {
		return $this->separator;
	}

}