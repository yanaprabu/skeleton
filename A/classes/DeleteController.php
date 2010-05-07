<?php

/**
 * 
 * 
 * @package Misc 
 */
class DeleteController {
	protected $template_file = '';
	protected $template_block_layout = '';
	protected $base_url = '';
	protected $fields = array();
	protected $db = null;
	protected $errorMsg = '';
	
	public function __construct($db) {
		$this->db = $db;
	}

	public function render($locator) {
		$config = $locator->get('Config');
		$request = $locator->get('Request');
		
		$id = $request->get('action', '/[^0-9]/');
		$name = $request->get('id', '/[^a-zA-Z0-9\ \-]/');

		$content = '';

		$template = new A_Template_Strreplace($this->template_file);
		$template->makeBlocks();
		
		if ($this->db->isError()) {
			echo $this->db->getErrorMsg() . '<br/>';
		}

		if ($row) {
			foreach ($rows as $row) {
				foreach ($this->fields as $field) {
					$template->set($field, $row[$field]);
				}
				$content .= $template->render($this->template_block_row);
			}
			$block = $this->template_block_layout;
		} else {
			$template->set('pagerlinks', '');
			$block = $this->template_block_none;
		}
		return $template->render($block);
	}

}
