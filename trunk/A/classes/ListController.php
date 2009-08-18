<?php

/**
 * 
 * 
 * @package Misc 
 */
class ListController {
	protected $template_file = '';
	protected $template_block_layout = 'listing';
	protected $template_block_none = 'listing_row';
	protected $template_block_row = 'listing_none';
	protected $base_url = '';
	protected $table;
	protected $fields = array();
	protected $order_by = array();
	protected $search_fields = array();
	protected $db = null;
	protected $pagerdb = null;
	
	public function __construct($db) {
		$this->db = $db;
	}

	public function setTemplate($file, $block_layout='', $block_row='', $block_none='') {
		$this->template_file = $file;
		if ($block_layout) $this->template_block_layout = $block_layout;
		if ($block_row) $this->template_block_row = $block_row;
		if ($block_none) $this->template_block_none = $block_none;
		return $this;
	}

	public function setBaseURL($url) {
		$this->base_url = $url;
		return $this;
	}

	public function setTable($table) {
		$this->table = $table;
		return $this;
	}

	public function setFields($fields) {
		$this->fields = $fields;
		return $this;
	}

	public function setOrderByFields($fields) {
		$this->order_by = $fields;
		return $this;
	}

	public function addSearchField($field, $param=null, $regex=null, $logic=null, $comparison=null, $prefix=null, $suffix=null) {
		if ($param == null) $param = 'search';
		if ($regex == null) $regex = '/[^a-zA-Z0-9\ \-]/';
		if ($logic == null) $logic = 'AND';
		if ($comparison == null) $comparison = 'LIKE';
		if ($prefix == null) $prefix = '%';
		if ($suffix == null) $suffix = '%';
		$this->search_fields[$field] = array('param'=>$param, 'regex'=>$regex, 'logic'=>$logic, 'comparison'=>$comparison, 'prefix'=>$prefix, 'suffix'=>$suffix);
		return $this;
	}

	public function addSearchRegex($regex) {
		$this->search_regex = $regex;
		return $this;
	}

	public function render($locator) {
		$config = $locator->get('Config');
		$request = $locator->get('Request');
		
		$id = $request->get('action', '/[^0-9]/');

		$content = '';

		$template = new A_Template_Strreplace($this->template_file);
		$template->makeBlocks();
		
		
		include_once 'A/Pager/DB.php';

		$sql = 'SELECT ' . implode(',', $this->fields) . ' FROM ' . $this->table . ' WHERE 1';
		if ($this->search_fields) {
			foreach ($this->search_fields as $field => $search) {
				$value = $request->get($search['param'], $search['regex']);
				if ($value != '') {
					$sql .= " {$search['logic']} $field {$search['comparison']} '{$search['prefix']}$value{$search['suffix']}'";
				} 
			}
		}
#echo "SQL=$sql<br/>";
		$pagerdb = new A_Pager_DB($sql, $this->db);
		
		include_once 'A/Pager.php';
		$pager = new A_Pager($pagerdb);
		if ($this->order_by) {
			$pager->setOrderByFields($this->order_by);
		}

		$pagerrequest = new A_Pager_Request_Session($pager);
		$pagerrequest->process();

		$rows = $pager->getRows();
		if ($pagerdb->db->isError()) {
			echo $pagerdb->db->getMessage() . '<br/>';
		}

		if ($rows) {
			foreach ($rows as $row) {
				foreach ($this->fields as $field) {
					$template->set($field, $row[$field]);
				}
				$content .= $template->render($this->template_block_row);
			}
			if ($pager->hasPages()) {
#				$rows = array();
			}
			$template->clear();
			$template->set('rows', $content);
			$pagerwriter = new A_Pager_HTMLWriter($pager);
			$pagerwriter->setBaseUrl($this->base_url);
			$spacer = '&nbsp;&nbsp;&nbsp;&nbsp;';
			$current_page = $pager->getCurrentPage();
			$last_page = $pager->getLastPage();
			$links = 'Page ' . $current_page . ' of ' . $last_page . '<br/>';
			if ($last_page > 1) {
				if ($current_page > 1) {
					if ($current_page > 2) {
						$links .= $pagerwriter->getFirstLink('<<') . $spacer;
					}
					$links .= $pagerwriter->getPrevLink('<') . $spacer;
				}
				$links .= implode($spacer, $pagerwriter->getRangeLinks());
				if ($current_page < $last_page) {
					$links .= $spacer . $pagerwriter->getNextLink('>');
					if ($current_page < ($last_page - 1)) {
						$links .= $spacer . $pagerwriter->getLastLink('>>');
					}
				}
			}
			$template->set('pagerlinks', $links);
		} else {
			$template->set('rows', $template->render($this->template_block_none));
			$template->set('pagerlinks', '');
		}
		return $template->render($this->template_block_layout);
	}

}
